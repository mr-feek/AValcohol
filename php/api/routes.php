<?php

use Util\Utils;

$app->get('/', function () use ($app) {
	echo 'yo pluto';
});

$app->group('/user', function() use ($app) {
	$app->get('/all', function() {
		Utils::respond(\Controller\UserController::getAll());
	});
	$app->post('/add', function () use ($app) {
		$unhashedPassword = $app->request->post('password');
		$email = $app->request->post('email');

		$user = UserController::addUser($unhashedPassword, $email);

		Utils::respond($user);
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