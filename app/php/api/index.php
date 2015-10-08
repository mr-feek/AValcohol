<?php

require '../../../vendor/autoload.php';

use Slim\Slim;
Slim::registerAutoloader();
use Controller\UserController;
use Controller\AlcoholController;
use Controller\EmailController;
use Util\Utils;

$app = new Slim(array(
	'debug' => true,
	'cookies.encrypt' => true,
	'cookies.secret_key' => 'hXbm%i&'
));

ORM::configure('mysql:host=localhost;dbname=AValcohol');
ORM::configure('username', 'root');
ORM::configure('password', 'feeksql');

// this requires all of the files within the routes directory to make the endpoints available
/*
$routeFiles = (array) glob('routes/*.php');
foreach($routeFiles as $routeFile) {
	require $routeFile;
}
*/

$app->get('/', function () use ($app) {
	echo 'yo pluto';
});

$app->group('/user', function () use ($app) {
	$app->post('/add', function () use ($app) {
		$unhashedPassword = $app->request->post('password');
		$email = $app->request->post('email');

		$user = UserController::addUser($unhashedPassword, $email);

		Utils::respond($user);
	});
});

$app->group('/alcohol', function () use ($app) {
	$app->get('/all', function () {
		Utils::respond(AlcoholController::getAll());
	});
});

$app->post('/email/send', function () use ($app) {
	$from = $app->request->post('from');
	$message = $app->request->post('message');
	$to = 'angela@avalcohol.com';
	$subject = 'Hello!';
	$headers = 'From: ' . $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	$data = EmailController::sendEmail($to, $subject, $message, $headers);

	Utils::respond($data);
});

/**
 * soft deletes the model
 */
function softDelete(Model $model)
{
	$model->deleted = 1;
	$model->save();
}

$app->run();
