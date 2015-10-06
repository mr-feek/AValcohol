<?php

require '../../../vendor/autoload.php';
require '../../../vendor/j4mie/paris/paris.php';
require '../models/Alcohol.php';

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$app = new \Slim\Slim();

ORM::configure('mysql:host=localhost;dbname=AValcohol');
ORM::configure('username', 'root');
ORM::configure('password', 'feeksql');

$app->get('/', function () use ($app) {
	echo 'yo pluto';
});

$app->get('/alcohols', function () use ($app) {
	$alcohols = Model::factory('Alcohol')->where('deleted', '0')->find_many();
	$data = array();

	foreach ($alcohols as $alcohol) {
		$data[] = $alcohol->as_array();
	}

	respond($data);
});

$app->post('/email/send', function () use ($app) {
	$from = $app->request->post('from');
	$message = $app->request->post('message');
	$to = 'angela@avalcohol.com';
	$subject = 'Hello!';
	$headers = 'From: ' . $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	$sent = mail($to, $subject, $message, $headers);

	$data = array(
		'success' => $sent
	);

	respond($data);
});

function respond($data)
{
	$data = json_encode($data);

	$app = \Slim\Slim::getInstance();
	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->setBody($data);
}

/**
 * soft deletes the model
 */
function softDelete($model)
{
	$model->deleted = 1;
	$model->save();
}

$app->run();
