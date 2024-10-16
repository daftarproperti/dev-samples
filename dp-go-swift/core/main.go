package main

import (
	"core/database"
	"core/handlers"
	"core/repositories"
	"fmt"
	"log"

	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
	"github.com/spf13/viper"
)

func init() {
	viper.SetConfigFile(".env")
	err := viper.ReadInConfig()
	if err != nil {
		log.Fatal("Error reading config file: ", err.Error())
	}

	if viper.GetBool(`debug`) {
		fmt.Println("service RUN on DEBUG mode")
	}
}

func main() {
	// Create a new echo server
	e := echo.New()

	// Add standard middleware
	e.Use(middleware.Logger())

	// Start the server
	db := database.StartMongoDB()

	// Setup the API Group
	api := e.Group("/api")

	// Setup the repositories
	listingRepo := repositories.NewListingRepo(db)

	// Setup the handlers
	handlers.NewCityHandler(api)
	handlers.NewListingHandler(api, listingRepo)

	e.Logger.Fatal(e.Start(fmt.Sprintf(":%d", 8080)))
}
