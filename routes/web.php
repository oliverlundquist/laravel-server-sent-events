<?php

use App\LiveDashboard\Http\Controllers\DashboardController;
use App\LiveDashboard\Http\Controllers\ShopController;
use App\ProgressBar\Http\Controllers\ProgressBarController;
use Illuminate\Support\Facades\Route;

// Example 1: Progress Bar
Route::get('/progress-bar', [ProgressBarController::class, 'progressBarView']);
Route::get('/sp-progress-bar/{request}', [ProgressBarController::class, 'shortPollingProgressBar']);
Route::get('/sse-progress-bar/{request}', [ProgressBarController::class, 'serverSentEventsProgressBar']);

// Example 2: Live Dashboard
Route::get('/', [ShopController::class, 'index']);
Route::get('/products/{id}', [ShopController::class, 'productDetails']);
Route::post('/ping', [ShopController::class, 'customerPing']);
//
// password protected routes, need to login to get access
//
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/tracking-data-stream', [DashboardController::class, 'trackingDataStream']);
    Route::post('/send-message-to-customer', [DashboardController::class, 'sendMessageToCustomer']);
});
