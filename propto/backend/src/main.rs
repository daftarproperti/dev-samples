mod db;
mod property;

use actix_cors::Cors;
use actix_web::http::header;
use actix_web::{web, App, HttpServer};
use dotenv::dotenv;
use mongodb::{options::ClientOptions, Client, Collection};
use std::env;
use std::io;
use std::path::Path;
use std::sync::Arc;

use db::PropertyDb;

type MongoCollection = Arc<Collection<PropertyDb>>;

async fn initialize_mongo_collection() -> MongoCollection {
    let parent_dir = Path::new("..");
    let _ = env::set_current_dir(&parent_dir);

    dotenv().ok();

    let mongodb_uri: String = env::var("MONGO_URI").expect("MONGO_URI must be set");

    let mongodb_database: String = env::var("DB_NAME").expect("DB_NAME must be set");

    let mongodb_collection: String =
        env::var("COLLECTION_NAME").expect("COLLECTION_NAME must be set");

    let client_options = ClientOptions::parse(&mongodb_uri)
        .await
        .expect("Failed to parse client options");

    let client = Client::with_options(client_options).expect("Failed to initialize MongoDB client");

    let database = client.database(&mongodb_database);
    let collection = database.collection::<PropertyDb>(&mongodb_collection);

    Arc::new(collection)
}

#[actix_web::main]
async fn main() -> io::Result<()> {
    let property_collection = initialize_mongo_collection().await;

    println!("Serving at port 8000 . . .");

    HttpServer::new(move || {
        let cors = Cors::default()
            .allow_any_origin()
            .allowed_methods(vec!["GET"])
            .allowed_headers(vec![header::CONTENT_TYPE, header::ACCEPT])
            .allow_any_header()
            .max_age(3600);

        App::new()
            .wrap(cors)
            .app_data(web::Data::new(property_collection.clone()))
            .service(property::list)
    })
    .bind("0.0.0.0:8000")?
    .run()
    .await
}
