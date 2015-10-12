<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/11/15
 * Time: 6:31 PM
 */

namespace Models;

use Faker\Generator;


class Product extends BaseModel
{
	public static function create_fake(\ORMWrapper $orm, Generator $faker) {
		$orm->create(array(
			'upc' => $faker->randomNumber(9),
			'name' => $faker->name,
			'price' => $faker->randomNumber(3)
		));

		return $orm;
	}
}