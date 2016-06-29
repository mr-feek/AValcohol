<?php

$app->get('/', function () use ($app) {
    return 'sup pluto';
});

$app->group(['prefix' => 'product', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'ProductController@getAllProductsForAddress');
});

$app->group(['prefix' => 'address', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'AddressController@create');
	// for now, because we don't have user accounts and can't enforce security,
	// all updated addresses will be treated as creating a new address
	//$app->put('{id}', 'AddressController@update');
	$app->put('{id}', 'AddressController@create');
	$app->get('delivery_zone', 'AddressController@getDeliveryZoneID');
});

$app->group(['prefix' => 'order', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', ['middleware' => 'delivery-hours', 'uses' => 'OrderController@createOrder']);
	// admin or vendor
	$app->patch('{order}/status', ['middleware' => 'jwt-auth', 'uses' => 'OrderStatusController@updateOrderStatus']);
});

$app->group(['prefix' => 'orders', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('pending', ['middleware' => 'jwt-auth', 'uses' => 'VendorController@getAllPendingOrders']);
});

$app->group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'UserController@create');
	$app->put('{id}', 'UserController@update');
	$app->get('', ['middleware' => 'jwt-auth', 'uses' => 'UserController@getFromToken']);
});

// todo: vendor middleware asserting user is vendor before doing request. this should happen before production? maybe? idk might be good
$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorController@get');
});

// todo: admin middleware asserting user has role before doing request. THIS NEEDS TO HAPPEN BEFORE PRODUCTION
$app->group(['prefix' => 'admin', 'middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('stats', 'StatController@getStats');
	$app->get('orders', 'AdminController@getOrders');
	$app->post('vendor', 'VendorController@create');
	$app->post('order/{id}/delivery-details', 'OrderDeliveryDetailsController@create');
	$app->get('order/{id}/delivery-details', 'OrderDeliveryDetailsController@get');
});

$app->group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('login', 'AuthController@login');
});

// ToDo: ADMIN AUTH
$app->group(['prefix' => 'site/status', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'SiteStatusController@get');
	$app->post('', 'SiteStatusController@save');
});

$app->get('/config', 'ConfigController@getConfig');

// ToDo: ADMIN AUTH
$app->get('/environment', function() use ($app) {
	return $app->environment();
});

$app->get('/stripe/key', function() use ($app) {
	return response()->json([
		'key' => env('STRIPE_KEY')
	]);
});

$app->post('/email/collect', 'UserController@lazySubmitToMailChimp');

if ($app->environment() !== 'production') {
	$app->group(['prefix' => 'logs', 'namespace' => '\Rap2hpoutre\LaravelLogViewer'], function ($app) {
		$app->get('', 'LogViewerController@index');
	});
}
