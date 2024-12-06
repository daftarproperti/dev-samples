<?php

namespace App\Providers;

use App\Models\Listing;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Route::bind('listing', function ($value) {
            return Listing::where('listingId', (int)$value)->firstOrFail();
        });
    }
}
