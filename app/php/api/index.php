<?php

require '../../../vendor/autoload.php';
require '../../../vendor/j4mie/paris/paris.php';
require '../models/Alcohol.php';

$app = new \Slim\Slim();

ORM::configure('mysql:host=localhost;dbname=AValcohol');
ORM::configure('username', 'root');
ORM::configure('password', 'feeksql');

$app->get('/', function() use($app) {
	echo 'yo pluto';
});

$app->get('/alcohols', function() use($app) {
	$alcohols = Model::factory('Alcohol')->where('deleted', '0')->find_many();
	$data = array();

	foreach ($alcohols as $alcohol) {
		$data[] = $alcohol->as_array();
	}

	respond($data);
});

function respond($data) {
	$data = json_encode($data);

	$app = \Slim\Slim::getInstance();
	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->setBody($data);
}

/**
 * soft deletes the model
 */
function softDelete($model) {
	$model->deleted = 1;
	$model->save();
}

$app->run();
