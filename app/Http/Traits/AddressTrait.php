<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/4/16
 * Time: 1:18 AM
 */

namespace App\Http\Traits;


use App\Models\User;
use App\Models\UserAddress;

trait AddressTrait
{

	/**
	 * Creates a new address and associates it with the given user
	 * @param $data
	 * @return mixed
	 */
	public function createAddress($data) {
		$user_id = $data['user']['id'];
		$user = User::findOrFail($user_id);
		$address = $user->addresses()->save(new UserAddress($data));

		return $address;
	}

	/**
	 * @param $zip
	 *
	 * If zip code is 16801, we can deliver
	 *
	 * @return bool
	 */
	protected function canDeliverToAddress($zip) {
		if ($zip === 16801 || $zip === '16801') {
			return true;
		}

		return false;
	}

	protected $cannot_deliver_message = "We're sorry, but at this time we can only deliver to the 16801 area";
}