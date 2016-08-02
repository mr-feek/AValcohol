<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/23/16
 * Time: 12:13 PM
 */

$app->group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->post('', 'UserController@create');
	$app->put('{id}', 'UserController@update');
	$app->get('', ['middleware' => 'jwt-auth', 'uses' => 'UserController@getFromToken']);
});