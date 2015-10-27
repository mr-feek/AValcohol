<?php

$root = $_SERVER["DOCUMENT_ROOT"];

require $root . '/vendor/autoload.php';
require '../util/config.php';

use Slim\Slim;
Slim::registerAutoloader();
use Controller\EmailController;
use Util\Utils;

$app = new Slim(array(
	'debug' => true,
	'cookies.encrypt' => true,
	'cookies.secret_key' => 'hXbm%i&'
));

ORM::configure(DB_URL);
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PASSWORD);

// this namespaces the models correctly for using the factory method
Model::$auto_prefix_models = 'Models\\';

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

$app->group('/user', function() use ($app) {
	$app->get('/all', function() {
		Utils::respond(\Controller\UserController::getAll());
	});
});

$app->group('/product', function() use ($app) {
	$app->get('/all', function() {
		Utils::respond(\Controller\ProductController::getAll());
	});
	$app->get('/featured', function() {
		Utils::respond(\Controller\ProductController::getAllFeatured());
	});
});

$app->group('/purchase', function() use ($app) {
	$app->get('/all', function() {
		Utils::respond(\Controller\PurchaseController::getAll());
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

$app->get('/seed', function() {
	\Controller\UserController::seed_one();
	\Controller\ProductController::seed_one();
	\Controller\PurchaseController::seed_one();
});

$app->get('/test', function() {

});

$app->run();
