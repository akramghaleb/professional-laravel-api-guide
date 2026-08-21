<?php

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CustomersController;
use App\Http\Controllers\Api\V1\CustomerOrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are registered by bootstrap/app.php and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('orders', OrderController::class)->except(['update']);
    Route::put('orders/{order}', [OrderController::class, 'replace']);

    Route::apiResource('customers', CustomersController::class);
    Route::apiResource('customers.orders', CustomerOrdersController::class)->except(['update']);
    Route::put('customers/{customer}/orders/{order}', [CustomerOrdersController::class, 'replace']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
