<?php

namespace App\Repositories;

use App\Models\Listing;

class ListingRepository
{
    public function get($params = [], $perPage = 12)
    {
        $query = Listing::query();

        $query->when(isset($params['search']), function ($query) use ($params) {
            $query->where('address', 'regexp', "/.*{$params['search']}.*/i")
                  ->orWhere('description', 'regexp', "/.*{$params['search']}.*/i");
        });

        $query->when(isset($params['city']), function ($query) use ($params) {
            $query->where('cityName', 'regexp', "/.*{$params['city']}.*/i");
        });

        $query->when(isset($params['bedroom']), function ($query) use ($params) {
            $query->where('bedroomCount', '>=', (int)$params['bedroom']);
        });

        $query->when(isset($params['bathroom']), function ($query) use ($params) {
            $query->where('bathRoomCount', '>=', (int)$params['bathroom']);
        });

        $query->when(isset($params['car']), function ($query) use ($params) {
            $query->where('carCount', '>=', (int)$params['car']);
        });

        $query->when(isset($params['price']), function ($query) use ($params) {
            $min = isset($params['price']['min']) ? (int)$params['price']['min'] : 0;
            $max = isset($params['price']['max']) ? (int)$params['price']['max'] : 0;

            if ($min || $max) {
                $query->whereBetween('price', [$min, $max]);
            }
        });

        $query->when(isset($params['lotSize']), function ($query) use ($params) {
            $min = isset($params['lotSize']['min']) ? (int)$params['lotSize']['min'] : 0;
            $max = isset($params['lotSize']['max']) ? (int)$params['lotSize']['max'] : 0;

            if ($min || $max) {
                $query->whereBetween('lotSize', [$min, $max]);
            }
        });

        $query->when(isset($params['type']), function ($query) use ($params) {
            $query->when($params['type'] == 'rent', function ($query) use ($params) {
                $query->where('listingForRent', true);
            });

            $query->when($params['type'] == 'sale', function ($query) use ($params) {
                $query->where('listingForSale', true);
            });
        });

        $results = $query->paginate($perPage);
        $results->appends($params);

        return $results;
    }
}
