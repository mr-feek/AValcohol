<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/8/15
 * Time: 12:32 PM
 */

namespace Util;

use Slim\Slim;

class Utils
{
	public static function respond($data)
	{
		$data = json_encode($data);

		$app = Slim::getInstance();
		$app->response->headers->set('Content-Type', 'application/json');
		$app->response->setBody($data);
	}
}