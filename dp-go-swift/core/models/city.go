package models

type PhotonResponse struct {
	Features []Feature `bson:"features" json:"features"`
}

type Feature struct {
	Properties Properties `bson:"properties" json:"properties"`
	Geometry   Geometry   `bson:"geometry" json:"geometry"`
}

type Properties struct {
	Name        string `bson:"name" json:"name"`
	State       string `bson:"state" json:"state"`
	Country     string `bson:"country" json:"country"`
	CountryCode string `bson:"countrycode" json:"countrycode"`
	OsmId       int64  `bson:"osm_id" json:"osm_id"`
}

type Geometry struct {
	Type        string    `bson:"type" json:"type"`
	Coordinates []float64 `bson:"coordinates" json:"coordinates"`
}

type CityResponse struct {
	Cities []City `bson:"cities" json:"cities"`
}

type City struct {
	Name        string   `bson:"name" json:"name"`
	State       string   `bson:"state" json:"state"`
	Country     string   `bson:"country" json:"country"`
	CountryCode string   `bson:"countrycode" json:"countrycode"`
	OsmId       int64    `bson:"osm_id" json:"osm_id"`
	Geometry    Geometry `bson:"geometry" json:"geometry"`
}
