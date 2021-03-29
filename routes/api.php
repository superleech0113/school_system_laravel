<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('tenancy')->group(function () {
    Route::post('stripe/webhook','ApiController@stripeWebhook')->name('api.stripe.webhook');
    Route::post('zoom/webhook', 'ApiController@zoomWebhook')->name('api.zoom.webhook');
    Route::post('line/webhook', 'ApiController@lineWebhook')->name('api.line.webhook');
});

Route::group(['prefix' => 'central'], function(){
    Route::group(['middleware' => ['tenant_api_auth']], function () {
        Route::post('tenant-subscription', 'ApiController@createTenantSubscription');
        Route::patch('tenant-subscription/{subscription_id}', 'ApiController@updateTenantSubscription');
    });
});
