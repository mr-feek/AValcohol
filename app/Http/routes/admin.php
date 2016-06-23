<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:14 PM
 */

// todo: admin middleware asserting user has role before doing request. THIS NEEDS TO HAPPEN BEFORE PRODUCTION
$app->group(['prefix' => 'admin', 'middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('stats', 'StatController@getStats');
	$app->get('orders', 'AdminController@getOrders');
	$app->post('vendor', 'VendorController@create');
	$app->post('order/{id}/delivery-details', 'OrderDeliveryDetailsController@create');
	$app->get('order/{id}/delivery-details', 'OrderDeliveryDetailsController@get');
});