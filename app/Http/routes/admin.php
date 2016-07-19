<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:14 PM
 */

/*
|--------------------------------------------------------------------------
| admin protected routes
|--------------------------------------------------------------------------
|
| require admin authentication
|
*/

$app->group(['prefix' => 'admin', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('stats', 'StatController@getStats');
	$app->get('orders', 'AdminController@getOrders');
	$app->post('vendor', 'VendorController@create');
	$app->post('order/{id}/delivery-details', 'OrderDeliveryDetailsController@create');
	$app->get('order/{id}/delivery-details', 'OrderDeliveryDetailsController@get');
});

$app->group(['prefix' => 'admin/vendor/login', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('{id}', 'AdminController@getLoginTokenForVendor');
});