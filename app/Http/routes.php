<?php

$app->get('/', function () use ($app) {
    return 'sup pluto';
});

$app->group(['prefix' => 'product', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'ProductController@getAllProductsForAddress');
});

// public (dont require vendor auth)
$app->group(['prefix' => 'vendor', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('{vendorId}/product/{productId}', 'VendorController@getProduct');
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
	$app->post('', ['middleware' => 'store-open', 'uses' => 'OrderController@createOrder']);
	// admin or vendor so jwt-auth will work for mvp launch
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

$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth|has-role:vendor', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorController@get');
});

$app->group(['prefix' => 'vendors', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'VendorsController@get');
});

$app->group(['prefix' => 'admin/vendor/login', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('{id}', 'AdminController@getLoginTokenForVendor');
});

$app->group(['prefix' => 'admin', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('stats', 'StatController@getStats');
	$app->get('orders', 'AdminController@getOrders');
	$app->post('vendor', 'VendorController@create');
	$app->post('order/{id}/delivery-details', 'OrderDeliveryDetailsController@create');
	$app->get('order/{id}/delivery-details', 'OrderDeliveryDetailsController@get');
});

$app->group(['prefix' => 'vendor', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('{id}/hours', 'VendorHoursController@create');
});

$app->group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('login', 'AuthController@login');
});

$app->group(['prefix' => 'site/status', 'middleware' => 'jwt-auth|has-role:administrator', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', 'SiteStatusController@get');
	$app->post('', 'SiteStatusController@save');
});

$app->get('/config', 'ConfigController@getConfig');

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
