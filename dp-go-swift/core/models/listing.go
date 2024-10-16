package models

import "go.mongodb.org/mongo-driver/v2/bson"

type Listing struct {
	ID              bson.ObjectID `bson:"_id" json:"id"`
	DpID            int64         `bson:"dp_id" json:"dp_id"`
	Title           string        `bson:"title" json:"title"`
	Description     string        `bson:"description" json:"description"`
	Address         string        `bson:"address" json:"address"`
	CityID          int64         `bson:"city_id" json:"city_id"`
	Price           int64         `bson:"price" json:"price"`
	RentPrice       int64         `bson:"rent_price" json:"rent_price"`
	PictureUrls     []string      `bson:"picture_urls" json:"picture_urls"`
	ListingForSale  bool          `bson:"listing_for_sale" json:"listing_for_sale"`
	ListingForRent  bool          `bson:"listing_for_rent" json:"listing_for_rent"`
	PropertyType    string        `bson:"property_type" json:"property_type"`
	BedroomCount    int64         `bson:"bedroom_count" json:"bedroom_count"`
	BathroomCount   int64         `bson:"bathroom_count" json:"bathroom_count"`
	CarCount        int64         `bson:"car_count" json:"car_count"`
	BuildingSize    int64         `bson:"building_size" json:"building_size"`
	LotSize         int64         `bson:"lot_size" json:"lot_size"`
	Ownership       string        `bson:"ownership" json:"ownership"`
	Facing          string        `bson:"facing" json:"facing"`
	ElectricalPower int64         `bson:"electrical_power" json:"electrical_power"`
	Source          string        `bson:"source" json:"source"`
	SourceURLs      string        `bson:"source_urls" json:"source_urls"`
	Registrant      *Registrant   `bson:"registrant" json:"registrant"`
	Coordinate      *Coordinate   `bson:"coordinate" json:"coordinate"`
}

type Registrant struct {
	Name              string `bson:"name" json:"name"`
	Company           string `bson:"company" json:"company"`
	ProfilePictureURL string `bson:"profile_picture_url" json:"profile_picture_url"`
}

type Coordinate struct {
	Coordinates []float64 `bson:"coordinates" json:"coordinates"`
}

type ListingResponse struct {
	Listings []Listing `bson:"listings" json:"listings"`
	Total    int       `bson:"total" json:"total"`
}

type ListingRequest struct {
	PriceRange       *Range    `bson:"price_range" json:"price_range"`
	ListingForSale   *bool     `bson:"listing_for_sale" json:"listing_for_sale"`
	ListingForRent   *bool     `bson:"listing_for_rent" json:"listing_for_rent"`
	PropertyType     *[]string `bson:"property_type" json:"property_type"`
	MinBedroomCount  *int      `bson:"min_bedroom_count" json:"min_bedroom_count"`
	MinBathroomCount *int      `bson:"min_bathroom_count" json:"min_bathroom_count"`
	BuildingSize     *Range    `bson:"building_size" json:"building_size"`
	LotSize          *Range    `bson:"lot_size" json:"lot_size"`
	MinCarCount      *int      `bson:"min_car_count" json:"min_car_count"`
	MinFloorCount    *int      `bson:"min_floor_count" json:"min_floor_count"`
	ElectricPower    *[]int    `bson:"electric_power" json:"electric_power"`
	Sorts            []string  `bson:"sorts" json:"sorts"`
	Page             *int      `bson:"page" json:"page"`
	Limit            *int      `bson:"limit" json:"limit"`
	CityId           *int      `bson:"city_id" json:"city_id"`
	Ownership        *[]string `bson:"ownership" json:"ownership"`
	Facing           *[]string `bson:"facing" json:"facing"`
	Geometry         *Geometry `bson:"geometry" json:"geometry"`
}

type Range struct {
	Min *int `bson:"min" json:"min"`
	Max *int `bson:"max" json:"max"`
}
