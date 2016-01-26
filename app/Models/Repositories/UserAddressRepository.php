<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Models\Repositories;

use App\Models\Entities\UserAddress;
use App\Models\Repositories\Interfaces\UserAddressInterface;
use App\Exceptions\APIException;
use App\Models\Entities\User;

class UserAddressRepository extends BaseRepository implements UserAddressInterface {
	protected $cannotDeliverMessage = '';

	public function __construct(UserAddress $address)
	{
		$this->model = $address;
	}

	public function getById($id) {
		return UserAddress::findOrFail($id);
	}

	public function update($data) {
		$user = UserAddress::findOrFail($data['id']);
		$user->update($data);
	}

	/**
	 * @param User $user
	 * @param $data
	 * @return mixed
	 */
	public function create(User $user, $data)
	{
		$address = $user->addresses()->save(new UserAddress($data));

		return $address;
	}

	public function isInDeliveryZone($long, $lat) {
		// to do
		return true;
	}

	public function canDeliverToAddress($data) {
		$this->cannotDeliverMessage = "We're sorry, but at this time we can only deliver to the 16801 area";
		return false;
	}

	/**
	 * Finds the address based on address Id and compares requesting user id with user id on record
	 * @param $address
	 * @param $user_id
	 * @return bool
	 */
	public function userCanUpdateAddress($address, $user_id) {
		if ($user_id !== $address->user_id) {
			return false;
		}
	}
}