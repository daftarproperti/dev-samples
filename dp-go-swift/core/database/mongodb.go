package database

import (
	"fmt"
	"log"

	"github.com/spf13/viper"
	"go.mongodb.org/mongo-driver/v2/mongo"
	"go.mongodb.org/mongo-driver/v2/mongo/options"
)

func StartMongoDB() *mongo.Database {
	var (
		HostURL  = viper.GetString("MONGO_URI")
		Database = viper.GetString("DB_NAME")
	)

	client, err := mongo.Connect(options.Client().ApplyURI(HostURL))
	if err != nil {
		log.Panic(err)
	}
	fmt.Println("Connected to MongoDB!")

	return client.Database(Database)
}
