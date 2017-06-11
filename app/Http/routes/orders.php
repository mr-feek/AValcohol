<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:13 PM
 */

$app->group(['prefix' => 'orders', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('pending', ['middleware' => 'jwt-auth', 'uses' => 'VendorController@getAllPendingOrders']);
});