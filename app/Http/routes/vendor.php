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

$app->group(['prefix' => 'vendors', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorsController@get');
});