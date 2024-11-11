This repo contains developer sample kits on how to use Daftar Properti's
open listing data and build on it.

# Core Concept

Daftar Properti pushes property listing updates to the blockchain for transparency
and high availability. During alpha, we use our test network (ganache.daftarproperti.org),
and will transition to the live Polygon network for General Availability (GA).

The key to building on Daftar Properti is to sync data from the blockchain to
your own database, allowing you to leverage the listings data for your own
applications, analytics, or custom services. Once synchronized, developers have
full control over the data to build innovative solutions on top of it.

# Quick Start

To get started, install `daftar-properti-sync` and its dependencies using npm:

```sh
npm install git+https://github.com/daftarproperti/daftar-properti-sync.git#551635a12d339f964db25979ed7a31735fbe0171 mongodb
```

`mongodb` is optional and only needed if you want to sync data to MongoDB.

## Usage

Below is a sample program to run the synchronizer and sync data to a MongoDB database.

```javascript
const { createInstance } = require('daftar-properti-sync');
const { MongoClient } = require("mongodb");

// During alpha stage, use our test network "ganache.daftarproperti.org".
// When this goes GA, you can use any provider like Infura or Alchemy.
const PROVIDER_URL = process.env.BLOCKCHAIN_PROVIDER_URL;

// During alpha stage, get the active contract and version from https://daftarproperti.org/_blockchain
const ABI_VERSION = process.env.DP_ABI_VERSION;
const CONTRACT_ADDRESS = process.env.DP_CONTRACT_ADDRESS;

// MongoDB Configuration
const MONGODB_URI = process.env.MONGODB_URI;
const MONBODB_DATABASE = process.env.MONGODB_DATABASE;
const MONGODB_COLLECTION_NAME = process.env.MONGODB_COLLECTION_NAME;

async function main() {
    try {
        const client = new MongoClient(MONGODB_URI);
        await client.connect();
        const listingCollection = client.db(MONGODB_DATABASE).collection(MONGODB_COLLECTION_NAME);

        const options = {
            port: 8050, // Synchronizer exposes a web interface for reporting and health check
            address: CONTRACT_ADDRESS,
            strictHash: true,
            providerHost: PROVIDER_URL,
            fetchAll: false,
            abiVersion: Number(ABI_VERSION),
            fromBlockNumber: 0,
            listingCollection: listingCollection, // Use built-in MongoDB target
            listingHandler: null, // Optional: Custom handler for listing change events
            errorHandling: {}, // Custom error handling (left empty in this example)
        };

        const instance = createInstance(options);

        await instance.start();
    } catch (error) {
        console.error('Error in main function:', error);
    }
}

main();
```

### Key Options

- MongoDB Integration: The synchronizer can save the property listings to a MongoDB collection using the `listingCollection` option.
- Custom Handlers: Use the `listingHandler` option to define custom logic for handling updates instead of storing in MongoDB.
- Health Check and Reporting: The synchronizer runs a web server on the specified port for health checks and simple reporting.

## Running the Synchronizer

To keep your data synchronized, you can run the synchronizer continuously in the background. This ensures real-time updates are always reflected in your database. However, if real-time data is not a requirement for your application, you can also run the synchronizer periodically to refresh the data.

Once the listings are synchronized to your database, you can build whatever app or service you need using the up-to-date listings data.

Some examples are provided depending your preferred tech stack:

* `propto`: Flutter multi-platform (runs on iOS/Android/Web)
* `samplekit-laravel`: Laravel-based website
* `dp-go-swift`: iOS ppp

You can use the examples as starting points, but these should not be limitations of what can be built.

## Getting Started

1. Install the dependencies listed above.
2. Set up a MongoDB instance (optional).
3. Configure the necessary environment variables, such as `BLOCKCHAIN_PROVIDER_URL`, `DP_ABI_VERSION`, `DP_CONTRACT_ADDRESS`, `MONGODB_URI`, etc.
4. Run the synchronizer script to start receiving data.

## Environment Variables
Ensure the following environment variables are set in your environment for the above script to work:
- `BLOCKCHAIN_PROVIDER_URL`: URL of the blockchain provider (e.g., "ganache.daftarproperti.org" during alpha stage).
- `DP_ABI_VERSION`, `DP_CONTRACT_ADDRESS`: These should be fetched from [Daftar Properti Blockchain Info](https://daftarproperti.org/_blockchain).
- `MONGODB_URI`, `MONGODB_DATABASE`, `MONGODB_COLLECTION_NAME`: MongoDB connection details.

## Support

For any questions, please join our [Early Adopter Program](https://daftarproperti.org/for-marketers) to get hands-on support.
