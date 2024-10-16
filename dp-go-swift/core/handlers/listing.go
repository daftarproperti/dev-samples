package handlers

import (
	"core/models"
	"core/repositories"
	"encoding/json"
	"net/http"

	"github.com/labstack/echo/v4"
)

const (
	DEFAULT_LATITUDE  = -6.175403
	DEFAULT_LONGITUDE = 106.824584
)

type ListingHandler struct {
	lr repositories.ListingRepo
}

func NewListingHandler(api *echo.Group, lr repositories.ListingRepo) {
	handler := &ListingHandler{lr}

	api.POST("/listings", handler.Fetch)
	api.GET("/listings/:id", handler.FetchById)
}

func (lh *ListingHandler) Fetch(c echo.Context) error {
	var input models.ListingRequest

	decoder := json.NewDecoder(c.Request().Body)
	err := decoder.Decode(&input)
	if err != nil {
		return c.JSON(http.StatusBadRequest, map[string]string{"error": err.Error()})
	}

	if input.Geometry == nil {
		input.Geometry = &models.Geometry{
			Type:        "Point",
			Coordinates: []float64{DEFAULT_LONGITUDE, DEFAULT_LATITUDE},
		}
	}

	res, err := lh.lr.Fetch(input)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
	}

	return c.JSON(http.StatusOK, res)
}

func (lh *ListingHandler) FetchById(c echo.Context) error {
	id := c.Param("id")
	res, err := lh.lr.FetchById(id)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
	}

	return c.JSON(http.StatusOK, res)
}
