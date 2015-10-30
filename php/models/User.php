<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/11/15
 * Time: 6:30 PM
 */

namespace Models;

use Faker\Generator;

class User extends BaseModel
{
	public static function create_fake(\ORMWrapper $orm, Generator $faker) {
		$orm->create(array(
			'email' => $faker->email,
			'password' => $faker->password
		));

		return $orm;
	}
}