<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:14 PM
 */

// todo: vendor middleware asserting user is vendor before doing request. this should happen before production? maybe? idk might be good
$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorController@get');
});