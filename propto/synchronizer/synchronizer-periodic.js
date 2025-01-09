const path = require('path');
const { createInstance } = require('daftar-properti-sync');
require('dotenv').config({ path: path.join(__dirname, '../.env') });
const { MongoClient } = require('mongodb');

const PROVIDER_URL = process.env.PROVIDER_URL;
const ABI_VERSION = process.env.ABI_VERSION;

// MongoDB Configuration
const MONGO_URI = process.env.MONGO_URI;
const DB_NAME = process.env.DB_NAME;
const COLLECTION_NAME = process.env.COLLECTION_NAME;

// Error Notification Configuration
const ERROR_NOTIF_CHANNEL = process.env.ERROR_NOTIF_CHANNEL;
const SLACK_WEBHOOK_URL = process.env.SLACK_WEBHOOK_URL;

// Time interval (in milliseconds)
const INTERVAL = 2 * 60 * 1000;

function createFetchLastKnownBlockNumber(listingCollection) {
    async function fetchLastKnownBlockNumber() {
        const latestListing = await listingCollection
            .find()
            .sort({ blockNumber: -1 })
            .limit(1)
            .toArray();

        if (latestListing.length == 0) {
            return 0;
        }
    
        const blockNumber = latestListing[0].blockNumber;
        console.log('Fetching from block number: ', blockNumber)
        return blockNumber;
    }
  
    return fetchLastKnownBlockNumber;
}

// Start the interval loop
async function main() {
    try {
        console.log('Starting periodic synchronization. Exit to stop');

        const client = new MongoClient(MONGO_URI);
        await client.connect();
        const listingCollection = client.db(DB_NAME).collection(COLLECTION_NAME);

        const options = {
            port: 8050,
            address: process.env.CONTRACT_ADDRESS,
            strictHash: true,
            providerHost: PROVIDER_URL,
            fetchAll: false,
            abiVersion: Number(ABI_VERSION),
            fromBlockNumber: 0,
            fetchLastKnownBlockNumber: createFetchLastKnownBlockNumber(listingCollection),
            listingCollection: listingCollection,
            listingHandler: null,
            errorHandling: {
              errorChannel: ERROR_NOTIF_CHANNEL,
              slackConfiguration: {
                slackWebhookURL: SLACK_WEBHOOK_URL
              },
            },
        };

        const instance = createInstance(options);

        await instance.fetchMissedListings();
    } catch (error) {
        console.error('Error in main function:', error);
    }
}

main();