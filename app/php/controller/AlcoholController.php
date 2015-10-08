<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/7/15
 * Time: 12:19 PM
 */

namespace Controller;

use Model;

class AlcoholController
{
	public static function getAll() {
		$alcohols = Model::factory('Models\\Alcohol')->where('deleted', '0')->find_many();
		$data = array();

		foreach ($alcohols as $alcohol) {
			$data[] = $alcohol->as_array();
		}

		return $data;
	}
}