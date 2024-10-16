package repositories

import "core/models"

type ListingRepo interface {
	Fetch(input models.ListingRequest) (res models.ListingResponse, err error)
	FetchById(id string) (res models.Listing, err error)
}
