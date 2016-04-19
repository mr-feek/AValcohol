<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Http\Repositories;

use App\Models\UserAddress;
use App\Http\Repositories\Interfaces\UserAddressInterface;
use App\Models\User;

class UserAddressRepository extends BaseRepository implements UserAddressInterface {
	protected $cannotDeliverMessage = '';

	public function __construct(UserAddress $address)
	{
		$this->model = $address;
	}

	public function getById($id) {
		$this->model = UserAddress::findOrFail($id);
		return $this->model;
	}

	public function update(UserAddress $model, $data) {
		$this->model = $model;
		$this->model->update($data);
		return $this->model;
	}

	/**
	 * Creates a new address and associates it with the given user
	 * @param User $user
	 * @param $data
	 * @return mixed
	 */
	public function create(User $user, $data)
	{
		$this->model = $user->address()->save(new UserAddress($data));
		return $this->model;
	}

	/**
	 * Determines if the given long / lat of the address is within our current delivery zone
	 * @param $long
	 * @param $lat
	 * @return bool
	 */
	public function isInDeliveryZone($long, $lat) {
		// to do
		return true;
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

		return true;
	}
}