<?php

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CustomersController;
use App\Http\Controllers\Api\V1\CustomerOrdersController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Resources\V1\UserResource;
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
    Route::patch('orders/{order}', [OrderController::class, 'update']);

    Route::apiResource('users', UserController::class)->except(['update']);
    Route::put('users/{user}', [UserController::class, 'replace']);
    Route::patch('users/{user}', [UserController::class, 'update']);

    Route::apiResource('customers', CustomersController::class)->except(['store','update','destroy']);
    Route::scopeBindings()->group(function () {
        Route::apiResource('customers.orders', CustomerOrdersController::class)->except(['update']);
        Route::put('customers/{customer}/orders/{order}', [CustomerOrdersController::class, 'replace']);
        Route::patch('customers/{customer}/orders/{order}', [CustomerOrdersController::class, 'update']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'message' => 'Authenticated user retrieved successfully.',
            'status' => 200,
            'data' => (new UserResource($request->user()))->resolve($request),
        ]);
    });
});
