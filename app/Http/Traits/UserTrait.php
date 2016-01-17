<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/4/16
 * Time: 2:14 AM
 */

namespace App\Http\Traits;

use App\Models\Order;
use App\Models\User;

trait UserTrait {
	/**
	 * Fulfills a charge to the given user id based on the value of order_id
	 * @param $user_id
	 * @param $order_id
	 * @param $stripe_token
	 * @return mixed
	 */
	public function chargeUserForOrder($user_id, $order_id, $stripe_token) {
		$user = User::find($user_id);
		$order = Order::find($order_id);
		$amount = $order->amount * 100; // charge amount needs to be converted to pennies

		$options = [
			'currency' => 'usd',
			'description' => 'test charge',
			'source' => $stripe_token,
			'metadata' => array(
				'user_id' => $user_id,
				'order_id' => $order_id
			)
		];

		return $user->charge($amount, $options);
	}

	public function createUser($data) {
		$user = User::create($data);
		// ensure this property is set..
		$user->mvp_user = true;
		$user->save();

		return $user;
	}

	public function updateUser($data) {
		$user = User::find($data['id']);

		$user->update($data);

		return $user;
	}
}