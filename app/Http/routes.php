<?php

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
