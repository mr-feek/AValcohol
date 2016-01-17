<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/4/16
 * Time: 1:18 AM
 */

namespace App\Http\Traits;


use App\Exceptions\APIException;
use App\Models\BlacklistedAddress;
use App\Models\User;
use App\Models\UserAddress;

trait AddressTrait
{
	protected $cannot_deliver_message = "";

	/**
	 * Creates a new address and associates it with the given user
	 * @param $data
	 * @return mixed
	 * @throws APIException
	 */
	public function createAddress($data) {
		if (!$this->canDeliverToAddress($data)) {
			throw new APIException($this->cannot_deliver_message);
		}

		$user_id = $data['user']['id'];
		$user = User::findOrFail($user_id);

		$address = $user->addresses()->save(new UserAddress($data));

		return $address;
	}

	/**
	 * @param $data
	 *
	 * If zip code is 16801 and address is not blacklisted, we can deliver
	 *
	 * @return bool
	 */
	protected function canDeliverToAddress($data) {
		if ($data['zipcode'] === 16801 || $data['zipcode'] === '16801') {
			unset($data['user']);

			$blacklisted = BlacklistedAddress::where($data)->first();

			if ($blacklisted) {
				$this->cannot_deliver_message = $blacklisted->getReason();
				return false;
			}

			return true;
		}

		$this->cannot_deliver_message = "We're sorry, but at this time we can only deliver to the 16801 area";
		return false;
	}
}