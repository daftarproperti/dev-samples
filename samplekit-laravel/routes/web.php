<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
Route::post('/receipts', [ReceiptController::class, 'store'])->name('receipts.store');

Route::get('/page/{page}', function ($page) {
    try {
        return view(sprintf('pages.%s', $page));
    } catch (\Throwable $th) {
        abort(404, 'Page not found');
    }
});
