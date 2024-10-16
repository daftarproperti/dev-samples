package handlers

import (
	"core/models"
	"encoding/json"
	"fmt"
	"io"
	"net/http"

	"github.com/labstack/echo/v4"
	"github.com/spf13/viper"
)

const (
	IndonesiaCountryCode = "ID"
	IndonesiaBoundingBox = "95.2930261576,-10.3599874813,141.03385176,5.47982086834"
)

func NewCityHandler(api *echo.Group) {
	api.GET("/cities", func(c echo.Context) error {
		query := c.QueryParam("q")
		photonUrl := viper.GetString("PHOTON_URL")
		url := fmt.Sprintf("%v?q=%v&osm_tag=:city&bbox=%v&limit=20", photonUrl, query, IndonesiaBoundingBox)
		resp, err := http.Get(url)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}
		defer resp.Body.Close()

		body, err := io.ReadAll(resp.Body)
		if err != nil {
			c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		var photonRes models.PhotonResponse
		if err := json.Unmarshal(body, &photonRes); err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		var CityResponse models.CityResponse
		for _, city := range photonRes.Features {
			if city.Properties.CountryCode == IndonesiaCountryCode {
				CityResponse.Cities = append(CityResponse.Cities, models.City{
					Name:        city.Properties.Name,
					State:       city.Properties.State,
					Country:     city.Properties.Country,
					CountryCode: city.Properties.CountryCode,
					OsmId:       city.Properties.OsmId,
					Geometry:    city.Geometry,
				})
			}
		}

		return c.JSON(http.StatusOK, CityResponse)
	})
}
