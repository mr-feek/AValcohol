<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return 'sup pluto';
});

$app->group(['prefix' => 'product', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('all', 'ProductController@getAll');
	$app->get('featured', 'ProductController@getAllFeatured');
	$app->get('beer', 'ProductController@getAllBeer');
});

$app->group(['prefix' => 'address', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'AddressController@create');
	// for now, because we don't have user accounts and can't enforce security,
	// all updated addresses will be treated as creating a new address
	//$app->put('{id}', 'AddressController@update');
	$app->put('{id}', 'AddressController@create');
});

$app->group(['prefix' => 'order', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', ['middleware' => 'delivery-hours', 'uses' => 'OrderController@createOrder']);

	// To Do: ADMIN AUTH
	$app->get('pending-and-out-for-delivery', 'OrderController@getAllPendingAndOutForDelivery');
	$app->get('{id}', 'OrderController@getfullOrderInfo');
	$app->post('status', 'OrderController@updateStatus');
});

$app->group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'UserController@create');
	$app->put('{id}', 'UserController@update');
});

$app->group(['prefix' => 'vendor', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('login', 'VendorController@login');
});

$app->get('/config', 'ConfigController@getConfig');

// To Do: ADMIN AUTH
$app->get('/environment', function() use ($app) {
	return $app->environment();
});

$app->get('/stripe/key', function() use ($app) {
	return response()->json([
		'key' => Dotenv::findEnvironmentVariable('STRIPE_KEY')
	]); // should i do this via config() helper?
});

if ($app->environment() !== 'production') {
	$app->group(['prefix' => 'logs', 'namespace' => '\Rap2hpoutre\LaravelLogViewer'], function ($app) {
		$app->get('', 'LogViewerController@index');
	});
}
