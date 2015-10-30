<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/8/15
 * Time: 1:55 PM
 */

namespace Controller;
use Model;

abstract class BaseController
{
	/**
	 * gets all associated models that are not marked as deleted
	 * @return array
	 */
	public static function getAll() {
		$modelName = self::getModelName();
		$models = Model::factory($modelName)->where('deleted', '0')->find_many();
		return self::prepareData($models);
	}

	/**
	 * finds the associating model name for the calling controller.
	 * IE if Model\UserController is the calling class, it will return 'User'
	 * @return string
	 */
	protected static function getModelName() {
		// will be something like "Controller\\UserController"
		$fullClassName = get_called_class();

		// strips namespace. will be something like UserController
		$shortClassName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);

		// remove the last ten characters (Controller). will be something like User
		$modelName = substr($shortClassName, 0, -10);

		return $modelName;
	}

	/*
	 * creates a fake model and saves it to the database. does not return anything
	 * right now. I HAVE CHANGED A LINE INSIDE Seeder.php to make this work
	 */
	public static function seed_one() {
		return \Seeder::seed(self::getModelName());
	}

	/**
	 * converts ORMWrapper instance(s) into an array of models and their data
	 * @param $models
	 * @return array
	 */
	protected static function prepareData($models) {
		$data = array();
		foreach ($models as $model) {
			$data[] = $model->as_array();
		}
		return $data;
	}

}