<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:12 PM
 */

$app->group(['prefix' => 'address', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'AddressController@create');
	// for now, because we don't have user accounts and can't enforce security,
	// all updated addresses will be treated as creating a new address
	//$app->put('{id}', 'AddressController@update');
	$app->put('{id}', 'AddressController@create');
	$app->get('delivery_zone', 'AddressController@getDeliveryZoneID');
});