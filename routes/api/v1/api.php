<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Symfony\Component\String\b;

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
        Route::post('/', 'FoodController@store');
        Route::patch('/{food}', 'FoodController@update');
        Route::delete('/{food}', 'FoodController@destroy');
    });
    
    
    // INGREDIENTS
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('/', 'IngredientController@index');
        Route::post('/', 'IngredientController@store');
        Route::patch('/{ingredient}', 'IngredientController@update');
        Route::delete('/{ingredient}', 'IngredientController@destroy');
    });
    
    // ORDERS
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', 'OrderController@index');
        Route::get('/{order}', 'OrderController@show');
        Route::patch('/{order}/cancel', 'OrderCancellationController@update');
    });
    
    // COUPONS
    Route::group(['prefix' => '/coupons'], function () {
        Route::get('/', 'CouponController@index');
        Route::get('/{coupon:code}', 'CouponController@show');
        Route::post('/', 'CouponController@store');
        Route::patch('/{coupon}', 'CouponController@update');
    });
    
    // Current user
    Route::get('/user', function () {
        return response()->json(['data' => Auth::user()]);
    });
});


// STRIPE WEBHOOK ROUTE
Route::stripeWebhooks('/payments/success');

// Create order
Route::post('/orders', 'OrderController@store');

// Get menu
Route::get('/foods', 'FoodController@index');
