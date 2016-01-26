<?php

namespace App\Models\Repositories;

use App\Models\Entities\User;
use App\Models\Repositories\Interfaces\UserInterface;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/24/16
 * Time: 11:55 PM
 */
class UserRepository extends BaseRepository implements UserInterface
{

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	public function getUserById($id)
	{
		return User::findOrFail($id);
	}

	public function create($data) {
		$user = User::create($data);
		// ensure this property is set..
		$user->mvp_user = true;
		$user->save();

		return $user;
	}

	public function update($data) {
		$user = User::find($data['id']);
		$user->update($data);
		return $user;
	}

	public function enforceUpdatePermissions($data) {
		// to do
	}

	public function enforceGetPermissions($data) {
		// to do
	}

	// Fulfills a charge to the given user id based on the value of order_id
	public function chargeUserForOrder(User $user, Order $order, $stripe_token) {
		$amount = $order->amount * 100; // charge amount needs to be converted to pennies

		$options = [
			'currency' => 'usd',
			'description' => 'test charge',
			'source' => $stripe_token,
			'receipt_email' => $user->email,
			'metadata' => array(
				'user_id' => $user->id,
				'order_id' => $order->id
			)
		];

		return $user->charge($amount, $options);
	}

}