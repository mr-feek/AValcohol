<?php

require '../../vendor/autoload.php';
require '../../vendor/j4mie/paris/paris.php';
require '../models/Alcohol.php';

use api\MyLogWriter;

$dotenv = new \Dotenv\Dotenv('../../');
$dotenv->load();

$environment = getenv('APP_ENV');
$debug = ($environment !== 'production') ? true : false; // temp, second should be false

if (!$debug) {
	// we're in production, don't display errors in case any happen from outside the slim app
	ini_set('display_errors', 0);
}

$app = new \Slim\Slim(array(
	'mode' => $environment,
	'debug' => $debug,
	'log.writer' => new MyLogWriter(),
	'log.enabled' => !$debug,
));

// db host might need to be changed to localhost idk..
$db_url = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE');

ORM::configure($db_url);
ORM::configure('username', getenv('DB_USERNAME'));
ORM::configure('password', getenv('DB_PASSWORD'));

$app->get('/', function() use($app) {
	// FOR TESTING EMAIL ERRORS
	$wrong = $right;
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

/**
 * user is signing up for soft launch
 */
$app->post('/email/create', function() use($app) {
	$email = $app->request->post('email');
	$record = ORM::for_table('mvp_signup')->create();
	$record->email = $email;
	$success = $record->save();

	$mailchimp = new MailChimp(getenv('MAILCHIMP_KEY'));
	$options = array(
		'id_list' => 'e2c9eed2ca',
		'email' => $email,
		'status' => 'subscribed',
		'interests' => array('52b6c69dc6', true)
	);
	$mailchimp->list_add_subscriber($options);

	respond(array(
		'success' => $success
	));
});

/**
 * Sends an email to angela
 */
$app->post('/email/send', function() use($app) {
	$from = $app->request->post('from');
	$message = $app->request->post('message');
	$to      = 'angela@avalcohol.com';
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
