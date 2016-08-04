<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:14 PM
 */

/*
|--------------------------------------------------------------------------
| public routes
|--------------------------------------------------------------------------
|
| don't require vendor auth
|
*/

$app->group(['prefix' => 'vendor', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('{vendorId}/product/{productId}', 'VendorController@getProduct');
});

/*
|--------------------------------------------------------------------------
| vendor protected routes
|--------------------------------------------------------------------------
|
| require vendor auth
|
*/

$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth|has-role:vendor', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorController@get');

});

/*
|--------------------------------------------------------------------------
| admin protected routes
|--------------------------------------------------------------------------
|
| require admin auth
|
*/

$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('{vendor_id}/hours', 'VendorHoursController@getWeeklyHours');
	$app->post('{vendor_id}/hours', 'VendorHoursController@create');
	$app->put('{vendor_id}/hours/{day_of_week}/id/{store_hours_id}', 'VendorHoursController@update');
	$app->delete('{vendor_id}/hours/{store_hours_id}', 'VendorHoursController@delete');

	$app->post('{vendor_id}/hours/override', 'VendorOverrideHoursController@create');
	$app->delete('{vendor_id}/hours/override/{model_id}', 'VendorOverrideHoursController@delete');

	$app->get('{id}', 'VendorController@getById');
});

$app->group(['prefix' => 'vendors', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorsController@get');
});