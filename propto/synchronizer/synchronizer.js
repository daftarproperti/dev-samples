const path = require('path');
const { createInstance } = require('daftar-properti-sync');
const { MongoClient } = require("mongodb");
require('dotenv').config({ path: path.join(__dirname, '../.env') });
require('fs').promises;

const PROVIDER_URL = process.env.PROVIDER_URL;
const ABI_VERSION = process.env.ABI_VERSION;

// MongoDB Configuration
const MONGO_URI = process.env.MONGO_URI;
const DB_NAME = process.env.DB_NAME;
const COLLECTION_NAME = process.env.COLLECTION_NAME;

// Error Notification Configuration
const ERROR_NOTIF_CHANNEL = process.env.ERROR_NOTIF_CHANNEL;
const SLACK_WEBHOOK_URL = process.env.SLACK_WEBHOOK_URL;

async function transformListing(listing, event) {
    let coordinate = {};
    coordinate.latitude = listing.coordinate.latitude;
    coordinate.longitude = listing.coordinate.longitude;

    if (listing.address) {
        coordinate.name = listing.address;
    }

    if (!listing.coordinate || !listing.coordinate.latitude || !listing.coordinate.longitude) {
        console.error('Error invalid coordinate: ', listing.coordinate);
        return null;
    }

    return {
      propertyId: event.id,
      blockNumber: event.blockNumber,
      bathroomCount: listing.bathroomCount,
      bedroomCount: listing.bedroomCount,
      buildingSize: listing.buildingSize,
      carCount: listing.carCount,
      address: listing.address,
      cityId: event.cityId,
      contacts: [{
        name: listing.registrant?.name ?? null,
        profilePictureUrl: listing.registrant?.profilePictureURL ?? listing.pictureUrls,
        provider: listing.registrant?.company ?? null,
        sourceUrl: event.offChainLink
      }],
      description: listing.description,
      electricalPower: listing.electricPower,
      facing: listing.facing,
      lotSize: listing.lotSize,
      ownership: listing.ownership,
      pictureUrls: listing.pictureUrls,
      price: {
        text: `Rp ${listing.price.toLocaleString('id-ID')}`,
        value: listing.price
      },
      source: "daftarproperti",
      title: listing.title,
      url: event.offChainLink,
      coordinate: coordinate,
    };
}

async function main() {
    try {
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

        await instance.start();
    } catch (error) {
        console.error('Error in main function:', error);
    }
}

main();
