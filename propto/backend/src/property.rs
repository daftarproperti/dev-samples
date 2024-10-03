use crate::db;
use crate::db::{PropertyDb, PropertyFilter};
use crate::MongoCollection;
use actix_web::{get, web, HttpResponse, Responder};
use futures::StreamExt;
use serde::{Deserialize, Serialize};

#[derive(Debug, Deserialize, Serialize)]
pub struct PropertyRequest {
    pub search_text: Option<String>,
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
    pub ownership: Option<String>,
}

#[derive(Debug, Deserialize, Serialize)]
pub struct PropertyResponse {
    pub id: String,
    pub title: String,
    pub address: String,
    pub description: String,
    pub city_id: i32,
    pub city_name: String,
    pub latitude: Option<f64>,
    pub longitude: Option<f64>,
    pub bathroom_count: i16,
    pub bedroom_count: i16,
    pub building_size: i32,
    pub lot_size: i32,
    pub car_count: i16,
    pub floor_count: i16,
    pub facing: String,
    pub is_verified: bool,
    pub listing_for_rent: bool,
    pub listing_for_sale: bool,
    pub ownership: String,
    pub picture_urls: Vec<String>,
    pub price: f64,
    pub updated_at: String,
    pub coordinate: Coordinate,
    pub registrant: Registrant,
}

#[derive(Debug, Deserialize, Serialize)]
pub struct Coordinate {
    pub latitude: f64,
    pub longitude: f64,
}

#[derive(Debug, Deserialize, Serialize)]
pub struct Registrant {
    pub name: String,
    pub phone_number_encrypted: String,
    pub phone_number_hash: String,
    pub profile_picture_url: String,
    pub company: String,
}

impl PropertyResponse {
    pub fn from_db(property_db: PropertyDb) -> Self {
        Self {
            id: property_db.id.to_hex(),
            title: property_db.title.unwrap_or_default(),
            address: property_db.address,
            description: property_db.description.unwrap_or_default(),
            city_id: property_db.city_id.unwrap_or_default(),
            city_name: property_db.city_name.unwrap_or_default(),
            latitude: property_db.coordinate.latitude,
            longitude: property_db.coordinate.longitude,
            bathroom_count: property_db.bathroom_count.unwrap_or(0) as i16,
            bedroom_count: property_db.bedroom_count.unwrap_or(0) as i16,
            building_size: property_db.building_size.unwrap_or_default(),
            lot_size: property_db.lot_size.unwrap_or_default(),
            car_count: property_db.car_count.map(|c| c as i16).unwrap_or(0),
            floor_count: property_db.floor_count.map(|f| f as i16).unwrap_or(1),
            facing: property_db.facing.unwrap_or_default(),
            is_verified: property_db.is_verified.unwrap_or_default(),
            listing_for_rent: property_db.listing_for_rent.unwrap_or_default(),
            listing_for_sale: property_db.listing_for_sale.unwrap_or_default(),
            ownership: property_db.ownership.unwrap_or_default(),
            picture_urls: property_db.picture_urls.unwrap_or_default(),
            price: property_db.price.unwrap_or_default(),
            updated_at: property_db.updated_at.unwrap_or_default(),

            registrant: property_db
                .registrant
                .map(|r| Registrant {
                    name: r.name.unwrap_or_default(),
                    phone_number_encrypted: r.phone_number_encrypted.unwrap_or_default(),
                    phone_number_hash: r.phone_number_hash.unwrap_or_default(),
                    profile_picture_url: r.profile_picture_url.unwrap_or_default(),
                    company: r.company.unwrap_or_default(),
                })
                .unwrap_or_else(|| Registrant {
                    name: String::new(),
                    phone_number_encrypted: String::new(),
                    phone_number_hash: String::new(),
                    profile_picture_url: String::new(),
                    company: String::new(),
                }),

            coordinate: Coordinate {
                latitude: property_db.coordinate.latitude.unwrap_or(0.0),
                longitude: property_db.coordinate.longitude.unwrap_or(0.0),
            },
        }
    }
}

#[get("/properties")]
pub async fn list(
    query: web::Query<PropertyRequest>,
    property_collection: web::Data<MongoCollection>,
) -> impl Responder {
    let filter = PropertyFilter {
        search_text: query.search_text.as_deref().unwrap_or_default(),
        min_bathroom_count: query.min_bathroom_count,
        max_bathroom_count: query.max_bathroom_count,
        min_bedroom_count: query.min_bedroom_count,
        max_bedroom_count: query.max_bedroom_count,
        min_building_size: query.min_building_size,
        max_building_size: query.max_building_size,
        min_lot_size: query.min_lot_size,
        max_lot_size: query.max_lot_size,
        min_price: query.min_price,
        max_price: query.max_price,
        ownership: query.ownership.as_deref().unwrap_or_default(),
    };

    let properties = match db::get_property(filter, property_collection.get_ref()).await {
        Ok(props) => props,
        Err(err) => {
            println!("Failed to fetch properties: {}", err);
            return HttpResponse::InternalServerError()
                .json("Error fetching properties from the database");
        }
    };

    let response: Vec<PropertyResponse> = properties
        .filter_map(|result| async { result.ok() })
        .map(PropertyResponse::from_db)
        .collect()
        .await;

    HttpResponse::Ok()
        .content_type("application/json")
        .json(response)
}
