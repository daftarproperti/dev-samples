<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Repositories\ListingRepository;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(ListingRepository $listingRepository, Request $request)
    {
        $viewsData = [
            'title' => 'Listings',
            'listings' => $listingRepository->get($request->except(['_token'])),
        ];

        return view('listings.index', $viewsData);
    }

    public function show(Listing $listing)
    {
        $viewsData = [
            'title' => $listing->typeName ? $listing->typeName . ' : ' . $listing->address : $listing->address,
            'listing' => $listing,
        ];

        return view('listings.detail', $viewsData);
    }
}
