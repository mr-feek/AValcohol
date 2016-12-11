<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:14 PM
 */

$app->group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers', 'middleware' => 'throttle:20,1'], function($app) {
	$app->post('login', 'AuthController@login');
});
