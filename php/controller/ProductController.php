<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/11/15
 * Time: 7:58 PM
 */

namespace Controller;

use Model;

class ProductController extends BaseController
{
	public static function getAllFeatured() {
		$models = Model::factory('Product')
			->filter('featured')
			->where('deleted', '0')
			->find_many();

		return self::prepareData($models);
	}
}