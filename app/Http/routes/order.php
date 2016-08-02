<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:13 PM
 */

$app->group(['prefix' => 'order', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', ['middleware' => 'store-open', 'uses' => 'OrderController@createOrder']);
	// admin or vendor so jwt-auth will work for mvp launch
	$app->patch('{order}/status', ['middleware' => 'jwt-auth', 'uses' => 'OrderStatusController@updateOrderStatus']);
});