const { createInstance } = require('daftar-properti-sync');
const { MongoClient } = require("mongodb");
const dotenv = require('dotenv');

dotenv.config();

async function transformListing(listing, event) {
    return {
        dp_id: BigInt(event.id),
        block_number: event.blockNumber,
        source_urls: event.offChainLink,
        bathroom_count: listing.bathroomCount,
        bedroom_count: listing.bedroomCount,
        building_size: listing.buildingSize,
        car_count: listing.carCount,
        address: listing.address,
        city_id: Number(event.cityId),
        registrant: {
            name: listing.registrant?.name,
            company: listing.registrant?.company,
            profile_picture_url: listing.registrant?.profilePictureURL ?? listing.pictureUrls[0],
        },
        description: listing.description,
        electrical_power: listing.electricPower,
        facing: listing.facing ?? "unknown",
        lot_size: listing.lotSize,
        ownership: listing.ownership ?? "unknown",
        picture_urls: listing.pictureUrls,
        price: listing.price,
        source: "daftarproperti",
        title: listing.title,
        property_type: listing.propertyType ?? "unknown",
        listing_for_sale: listing.listingForSale,
        listing_for_rent: listing.listingForRent,
        rent_price: listing.rentPrice,
        coordinate: {
            type: "Point",
            coordinates: [
                listing.coordinate.longitude ?? 0, 
                listing.coordinate.latitude ?? 0,
            ],
        },
      };
}

function createListingHandler(listingCollection) {
    async function listingHandler(listing, event) {
        const dpListing = await transformListing(listing, event);

        if (!dpListing) {
            console.log(`not handling listing ${event.id}`)
            return;
        }

        const filter = { dp_id: dpListing.dp_id };
        try {
            switch (event.operationType) {
                case "DELETE":
                    const deleteResult = await listingCollection.deleteOne(filter);
                    if (deleteResult.deletedCount > 0) {
                        console.log(`Listing deleted:  ${dpListing.block_number}`);
                    } else {
                        console.log(`Listing ${dpListing.dp_id} not found in mongodb for deletion, block number ${dpListing.block_number}`);
                    }
                    break;
    
                case "ADD":
                case "UPDATE":
                    const update = { $set: dpListing };
                    const options = { upsert: true };

                    if (event.operationType === "ADD") {
                        await listingCollection.createIndex({ coordinate: "2dsphere" }, { name: "coordinate_2dsphere" });
                    }

                    const updateResult = await listingCollection.updateOne(filter, update, options);
                    if (updateResult.upsertedCount > 0) {
                        console.log(`Listing inserted: ${dpListing.block_number}`);
                    } else {
                        console.log(`Listing updated: ${dpListing.block_number}`);
                    }
                    break;
    
                default:
                    console.log(`Invalid operationType: ${event.operationType} for listing ${dpListing.dp_id}, block number ${event.blockNumber}`);
            }
        } catch (error) {
            throw (error);
        }
    }

    return listingHandler;
}

const fetchLastKnownBlockNumber = async () => {
    return 0;
};

const errorHandler = async (error, context) => {
    console.error(`Error occurred! Error: ${error}, context: ${context}`);
};

async function main() {
    try {
        const MONGO_URI = process.env.MONGO_URI;
        const DB_NAME = process.env.DB_NAME;
        const COLLECTION_NAME = process.env.COLLECTION_NAME;
        const PROVIDER_URL = process.env.PROVIDER_URL;
        const ERROR_NOTIF_CHANNEL = process.env.ERROR_NOTIF_CHANNEL;
        const SLACK_WEBHOOK_URL = process.env.SLACK_WEBHOOK_URL;
        const CONTRACT_ADDRESS = process.env.CONTRACT_ADDRESS;

        if (!PROVIDER_URL || !ERROR_NOTIF_CHANNEL || !SLACK_WEBHOOK_URL || !CONTRACT_ADDRESS) {
            throw new Error("Missing environment variables. Please set PROVIDER_URL, ERROR_NOTIF_CHANNEL, SLACK_WEBHOOK_URL, or CONTRACT_ADDRESS.");
        }

        const client = new MongoClient(MONGO_URI);
        await client.connect();
        const listingCollection = client.db(DB_NAME).collection(COLLECTION_NAME);

        const options = {
            port: 8080,
            providerHost: PROVIDER_URL,
            address: CONTRACT_ADDRESS,
            fetchAll: false,
            strictHash: true,
            fromBlockNumber: 0,
            abiVersion: 0,
            fetchLastKnownBlockNumber: fetchLastKnownBlockNumber,
            listingHandler: createListingHandler(listingCollection),
            errorHandling: {
                errorChannel: ERROR_NOTIF_CHANNEL,
                slackConfiguration: {
                    slackWebhookURL: SLACK_WEBHOOK_URL
                },
                errorHandler: errorHandler,
            },
        };

        const instance = createInstance(options);

        await instance.start();
    } catch (error) {
        console.error('Error in main function:', error);
    }
}

main();