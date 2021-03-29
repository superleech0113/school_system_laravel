<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'central'], function(){
    Route::get('/', function() {
        return 'Cetnral App Home';
    })->name('central.home');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
    Route::get('helper', 'HelperController@index');

    Route::get('site/{subscription_id}', 'TenantController@navigateTenant');
    Route::post('site/create/{subscription_id}', 'TenantController@store')->name('tenant.store');
});