use mongodb::bson::oid::ObjectId;
use mongodb::error::Error;
use mongodb::{bson::doc, bson::Document, Collection};
use serde::{Deserialize, Serialize};

#[derive(Debug, Serialize, Deserialize)]
pub struct PropertyDb {
    #[serde(rename = "_id")]
    pub id: ObjectId,

    #[serde(rename = "listingId")]
    pub listing_id: Option<i64>,

    #[serde(rename = "bathroomCount")]
    pub bathroom_count: Option<i32>,

    #[serde(rename = "bedroomCount")]
    pub bedroom_count: Option<i32>,

    #[serde(rename = "buildingSize")]
    pub building_size: Option<i32>,

    #[serde(rename = "carCount")]
    pub car_count: Option<i32>,

    #[serde(rename = "cityId")]
    pub city_id: Option<i32>,

    #[serde(rename = "cityName")]
    pub city_name: Option<String>,

    #[serde(rename = "electricPower")]
    pub electric_power: Option<i32>,

    #[serde(rename = "floorCount")]
    pub floor_count: Option<i32>,

    #[serde(rename = "isMultipleUnits")]
    pub is_multiple_units: Option<bool>,

    #[serde(rename = "isVerified")]
    pub is_verified: Option<bool>,

    #[serde(rename = "listingForRent")]
    pub listing_for_rent: Option<bool>,

    #[serde(rename = "listingForSale")]
    pub listing_for_sale: Option<bool>,

    #[serde(rename = "listingIdStr")]
    pub listing_id_str: Option<String>,

    #[serde(rename = "lotSize")]
    pub lot_size: Option<i32>,

    #[serde(rename = "pictureUrls")]
    pub picture_urls: Option<Vec<String>>,

    #[serde(rename = "propertyType")]
    pub property_type: Option<String>,

    #[serde(rename = "rentPrice")]
    pub rent_price: Option<i64>,

    #[serde(rename = "updatedAt")]
    pub updated_at: Option<String>,

    #[serde(rename = "withRewardAgreement")]
    pub with_reward_agreement: Option<bool>,

    pub title: Option<String>,
    pub address: String,
    pub coordinate: CoordinateDb,
    pub description: Option<String>,
    pub facing: Option<String>,
    pub ownership: Option<String>,
    pub registrant: Option<RegistrantDb>,
    pub price: Option<f64>,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct CoordinateDb {
    pub latitude: Option<f64>,
    pub longitude: Option<f64>,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct RegistrantDb {
    pub name: Option<String>,
    pub company: Option<String>,

    #[serde(rename = "phoneNumberEncrypted")]
    pub phone_number_encrypted: Option<String>,

    #[serde(rename = "phoneNumberHash")]
    pub phone_number_hash: Option<String>,

    #[serde(rename = "profilePictureURL")]
    pub profile_picture_url: Option<String>,
}

pub struct PropertyFilter<'a> {
    pub search_text: &'a str,
    pub min_bathroom_count: Option<i16>,
    pub max_bathroom_count: Option<i16>,
    pub min_bedroom_count: Option<i16>,
    pub max_bedroom_count: Option<i16>,
    pub min_building_size: Option<i32>,
    pub max_building_size: Option<i32>,
    pub min_lot_size: Option<i32>,
    pub max_lot_size: Option<i32>,
    pub min_price: Option<i64>,
    pub max_price: Option<i64>,
    pub ownership: &'a str,
}

pub async fn get_property(
    filter: PropertyFilter<'_>,
    collection: &Collection<PropertyDb>,
) -> Result<mongodb::Cursor<PropertyDb>, Error> {
    let query = construct_mongo_query(filter);

    let cursor = collection.find(query, None).await?;
    Ok(cursor)
}

fn construct_mongo_query(filter: PropertyFilter) -> Document {
    let mut query = Document::new();

    if !filter.search_text.is_empty() {
        query.insert(
            "$or",
            vec![
                doc! {"title": {"$regex": filter.search_text, "$options": "i"}},
                doc! {"address": {"$regex": filter.search_text, "$options": "i"}},
                doc! {"description": {"$regex": filter.search_text, "$options": "i"}},
            ],
        );
    }

    if filter.min_bathroom_count.is_some() || filter.max_bathroom_count.is_some() {
        let mut bathroom_query = doc! {};
        if let Some(min_bathroom) = filter.min_bathroom_count {
            bathroom_query.insert("$gte", min_bathroom as i32);
        }
        if let Some(max_bathroom) = filter.max_bathroom_count {
            bathroom_query.insert("$lte", max_bathroom as i32);
        }
        query.insert("bathroomCount", bathroom_query);
    }

    if filter.min_bedroom_count.is_some() || filter.max_bedroom_count.is_some() {
        let mut bedroom_query = doc! {};
        if let Some(min_bedroom) = filter.min_bedroom_count {
            bedroom_query.insert("$gte", min_bedroom as i32);
        }
        if let Some(max_bedroom) = filter.max_bedroom_count {
            bedroom_query.insert("$lte", max_bedroom as i32);
        }
        query.insert("bedroomCount", bedroom_query);
    }

    if filter.min_building_size.is_some() || filter.max_building_size.is_some() {
        let mut building_size_query = doc! {};
        if let Some(min_building_size) = filter.min_building_size {
            building_size_query.insert("$gte", min_building_size as i32);
        }
        if let Some(max_building_size) = filter.max_building_size {
            building_size_query.insert("$lte", max_building_size as i32);
        }
        query.insert("buildingSize", building_size_query);
    }

    if filter.min_lot_size.is_some() || filter.max_lot_size.is_some() {
        let mut lot_size_query = doc! {};
        if let Some(min_lot_size) = filter.min_lot_size {
            lot_size_query.insert("$gte", min_lot_size as i32);
        }
        if let Some(max_lot_size) = filter.max_lot_size {
            lot_size_query.insert("$lte", max_lot_size as i32);
        }
        query.insert("lotSize", lot_size_query);
    }

    if filter.min_price.is_some() || filter.max_price.is_some() {
        let mut price_query = doc! {};
        if let Some(min_price) = filter.min_price {
            price_query.insert("$gte", min_price as i32);
        }
        if let Some(max_price) = filter.max_price {
            price_query.insert("$lte", max_price as i32);
        }
        query.insert("price", price_query);
    }

    if !filter.ownership.is_empty() {
        query.insert("ownership", filter.ownership);
    }

    return query;
}
