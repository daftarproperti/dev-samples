//
//  Listing.swift
//  swift
//
//  Created by airm1 on 07/10/24.
//

import Foundation

var ListingDummy: Listing = load("Listing.json")

struct Listing: Codable, Identifiable {
    var id: String
    var dp_id: Int64
    var title: String
    var description: String
    var address: String
    var city_id: Int64
    var price: Int64
    var rent_price: Int64
    var picture_urls: [String]
    var listing_for_sale: Bool
    var listing_for_rent: Bool
    var property_type: String
    var bedroom_count: Int64
    var bathroom_count: Int64
    var car_count: Int64
    var building_size: Int64
    var lot_size: Int64
    var ownership: String
    var facing: String
    var electrical_power: Int64
    var source: String
    var source_urls: String
    var registrant: Registrant?
    var coordinate: Coordinate?
}

struct Registrant: Codable {
    var name: String
    var company: String
    var profile_picture_url: String
}

struct Coordinate: Codable {
    var coordinates: [Double]
}

struct ListingResponse: Codable {
    var listings: [Listing]
    var total: Int
}

struct ListingRequest: Codable {
    var price_range: Range?
    var listing_for_sale: Bool?
    var listing_for_rent: Bool?
    var property_type: [String]?
    var min_bedroom_count: Int?
    var min_bathroom_count: Int?
    var building_size: Range?
    var lot_size: Range?
    var min_car_count: Int?
    var min_floor_count: Int?
    var electric_power: [Int]?
    var sorts: [String]?
    var page: Int?
    var limit: Int?
    var city_id: Int?
    var ownership: [String]?
    var facing: [String]?
    var geometry: Geometry?
}

struct Range: Codable {
    var min: Int?
    var max: Int?
}

struct Geometry: Codable {
    var type: String
    var coordinates: [Double]
}
