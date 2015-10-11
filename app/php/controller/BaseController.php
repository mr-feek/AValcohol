<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/8/15
 * Time: 1:55 PM
 */

namespace Controller;
use Models\User;

abstract class BaseController
{
	/*
	 * creates a fake model and saves it to the database. does not return anything
	 * right now. I HAVE CHANGED A LINE INSIDE Seeder.php to make this work
	 */
	public static function seed_one() {
		// will be something like "Controller\\UserController"
		$fullClassName = get_called_class();

		// strips namespace. will be something like UserController
		$shortClassName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);

		// remove the last ten characters (Controller). will be something like User
		$modelName = substr($shortClassName, 0, -10);

		return \Seeder::seed($modelName);
	}
}