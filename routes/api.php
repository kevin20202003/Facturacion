<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([\App\Http\Middleware\ApiTokenMiddleware::class])->group(function () {
    // Clients
    Route::get('clients', [\App\Http\Controllers\Api\ClientApiController::class, 'index']);
    Route::post('clients', [\App\Http\Controllers\Api\ClientApiController::class, 'store']);
    Route::get('clients/{client}', [\App\Http\Controllers\Api\ClientApiController::class, 'show']);
    Route::put('clients/{client}', [\App\Http\Controllers\Api\ClientApiController::class, 'update']);
    Route::delete('clients/{client}', [\App\Http\Controllers\Api\ClientApiController::class, 'destroy']);

    // Products
    Route::get('products', [\App\Http\Controllers\Api\ProductApiController::class, 'index']);
    Route::post('products', [\App\Http\Controllers\Api\ProductApiController::class, 'store']);
    Route::get('products/{product}', [\App\Http\Controllers\Api\ProductApiController::class, 'show']);
    Route::put('products/{product}', [\App\Http\Controllers\Api\ProductApiController::class, 'update']);
    Route::delete('products/{product}', [\App\Http\Controllers\Api\ProductApiController::class, 'destroy']);

    // Invoices
    Route::get('invoices', [\App\Http\Controllers\Api\InvoiceApiController::class, 'index']);
    Route::post('invoices', [\App\Http\Controllers\Api\InvoiceApiController::class, 'store']);
    Route::get('invoices/{invoice}', [\App\Http\Controllers\Api\InvoiceApiController::class, 'show']);
});
