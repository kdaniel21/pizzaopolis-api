<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderCancellationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngredientController;

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

// AUTH
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false
]);

// ADMIN ROUTES
Route::group(['middleware' => 'auth:sanctum'], function () {

    // FOODS
    Route::group(['prefix' => 'foods'], function () {
        Route::post('/', [FoodController::class, 'store']);
        Route::get('/all', [FoodController::class, 'index']);
        Route::patch('/{food}', [FoodController::class, 'update']);
        Route::delete('/{food}', [FoodController::class, 'destroy']);
    });
    
    
    // INGREDIENTS
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('/', [IngredientController::class, 'index']);
        Route::post('/', [IngredientController::class, 'store']);
        Route::patch('/{ingredient}', [IngredientController::class, 'update']);
        Route::delete('/{ingredient}', [IngredientController::class, 'destroy']);
    });

    // FOOD CATEGORIES
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });
    
    // ORDERS
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::patch('/{order}/cancel', [OrderCancellationController::class, 'update']);
    });
    
    // COUPONS
    Route::group(['prefix' => '/coupons'], function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::post('/', [CouponController::class, 'store']);
        Route::patch('/{coupon}', [CouponController::class, 'update']);
    });
    
    // Current user
    Route::get('/user', [CurrentUserController::class, 'show']);
});


// STRIPE WEBHOOK ROUTE
Route::stripeWebhooks('/payments/success');
// Check coupon validity
Route::get('/coupons/{coupon:code}', [CouponController::class, 'show']);

// Create order
Route::post('/orders', [OrderController::class, 'store']);

// Get menu
Route::get('/foods', [FoodController::class, 'indexPublic']);
