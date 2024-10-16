package repositories

import (
	"context"
	"core/models"
	"fmt"
	"strings"
	"time"

	"go.mongodb.org/mongo-driver/v2/bson"
	"go.mongodb.org/mongo-driver/v2/mongo"
)

type listingRepo struct {
	db *mongo.Database
}

func NewListingRepo(db *mongo.Database) *listingRepo {
	return &listingRepo{db}
}

func (lr *listingRepo) Fetch(input models.ListingRequest) (res models.ListingResponse, err error) {
	ctx, cancel := context.WithTimeout(context.Background(), 100*time.Second)

	pipeline := BuildPipeline(input)
	cur, err := lr.db.Collection("listings").Aggregate(ctx, pipeline)
	defer cancel()

	if err != nil {
		return models.ListingResponse{}, err
	}

	res.Total = cur.RemainingBatchLength()
	if err := cur.All(ctx, &res.Listings); err != nil {
		return models.ListingResponse{}, err
	}

	return res, nil
}

func (lr *listingRepo) FetchById(id string) (res models.Listing, err error) {
	listingId, err := bson.ObjectIDFromHex(id)
	if err != nil {
		return models.Listing{}, err
	}

	ctx, cancel := context.WithTimeout(context.Background(), 100*time.Second)

	err = lr.db.Collection("listings").FindOne(ctx, bson.M{"_id": listingId}).Decode(&res)
	defer cancel()

	if err != nil {
		return models.Listing{}, err
	}

	return res, nil
}

func BuildPipeline(input models.ListingRequest) (pipeline []bson.M) {
	pipeline = []bson.M{
		{
			"$geoNear": bson.M{
				"distanceField":      "distance",
				"distanceMultiplier": 6371,
				"spherical":          true,
				"near": bson.M{
					"type":        "Point",
					"coordinates": []float64{input.Geometry.Coordinates[0], input.Geometry.Coordinates[1]},
				},
			},
		},
		{
			"$match": bson.M{}, // Placeholder for filtering criteria
		},
		{
			"$sort": bson.M{}, // Placeholder for sorting criteria
		},
		{
			"$skip": 0, // Placeholder for pagination
		},
		{
			"$limit": 10, // Placeholder for pagination
		},
	}

	// Handling the filtering criteria
	filter := bson.M{}

	// Adding filtering conditions based on input...
	if input.PriceRange != nil {
		filter["price"] = bson.M{"$gte": input.PriceRange.Min, "$lte": input.PriceRange.Max}
	}
	if input.ListingForSale != nil && *input.ListingForSale {
		filter["listingForSale"] = true
	}
	if input.ListingForRent != nil && *input.ListingForRent {
		filter["listingForRent"] = true
	}
	if input.PropertyType != nil && len(*input.PropertyType) > 0 {
		filter["propertyType"] = bson.M{"$in": *input.PropertyType}
	}
	if input.MinBedroomCount != nil {
		filter["bedroomCount"] = bson.M{"$gte": *input.MinBedroomCount}
	}
	if input.MinBathroomCount != nil {
		filter["bathroomCount"] = bson.M{"$gte": *input.MinBathroomCount}
	}
	if input.BuildingSize != nil {
		filter["buildingSize"] = bson.M{"$gte": input.BuildingSize.Min, "$lte": input.BuildingSize.Max}
	}
	if input.LotSize != nil {
		filter["lotSize"] = bson.M{"$gte": input.LotSize.Min, "$lte": input.LotSize.Max}
	}
	if input.MinCarCount != nil {
		filter["carCount"] = bson.M{"$gte": *input.MinCarCount}
	}
	if input.MinFloorCount != nil {
		filter["floorCount"] = bson.M{"$gte": *input.MinFloorCount}
	}
	if input.ElectricPower != nil && len(*input.ElectricPower) > 0 {
		filter["electricPower"] = bson.M{"$in": *input.ElectricPower}
	}
	if input.CityId != nil {
		filter["cityId"] = *input.CityId
	}
	if input.Ownership != nil && len(*input.Ownership) > 0 {
		filter["ownership"] = bson.M{"$in": *input.Ownership}
	}
	if input.Facing != nil && len(*input.Facing) > 0 {
		filter["facing"] = bson.M{"$in": *input.Facing}
	}

	// Set the filter criteria in the `$match` stage
	pipeline[1]["$match"] = filter

	// Handling sorting criteria
	sort := bson.M{"distance": 1} // Default to sort by distance
	for _, s := range input.Sorts {
		parts := strings.Split(s, ":")
		if len(parts) == 2 {
			field := parts[0]
			order := 1
			if parts[1] == "desc" {
				order = -1
			}
			sort[field] = order
		}
	}
	pipeline[2]["$sort"] = sort

	// Handling pagination
	if input.Page != nil && input.Limit != nil {
		skip := (*input.Page - 1) * (*input.Limit)
		pipeline[3]["$skip"] = skip
		pipeline[4]["$limit"] = *input.Limit
	} else {
		// Default skip and limit
		pipeline[3]["$skip"] = 0
		pipeline[4]["$limit"] = 10
	}

	// Debugging print statement (optional)
	fmt.Printf("%+v\n", pipeline)

	return pipeline
}
