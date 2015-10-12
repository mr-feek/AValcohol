<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/11/15
 * Time: 6:32 PM
 */

namespace Models;

use Faker\Generator;


class Purchase extends BaseModel
{
	public function user() {
		return $this->has_one('User');
	}

	public function product() {
		return $this->has_one('Product');
	}

	public static function create_fake(\ORMWrapper $orm, Generator $faker) {
		$orm->create(array(
			'user_id' => '50', // make sure this exists obv
			'product_id' => '1', // make sure this exists obv
			'amount' => '100.00'
		));

		return $orm;
	}
}