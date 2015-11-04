<?php
/**
 * This class sets up the slim app instance and database connections, as well as any other
 * app specific info
 */
$root = $_SERVER["DOCUMENT_ROOT"] ?: realpath(__DIR__ . '/../..');

require $root . '/vendor/autoload.php';
require $root . '/php/util/config.php';

use Slim\Slim;
Slim::registerAutoloader();

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