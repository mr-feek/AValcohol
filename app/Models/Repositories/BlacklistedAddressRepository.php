<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:36 AM
 */

namespace App\Models\Repositories;

use App\Models\Entities\BlacklistedAddress;
use App\Models\Repositories\Interfaces\BlacklistedAddressInterface;

class BlacklistedAddressRepository extends BaseRepository implements BlacklistedAddressInterface
{
	public function __construct(BlacklistedAddress $address)
	{
		$this->model = $address;
	}

	public function get($street, $city, $state, $zipcode)
	{
		/**
		 * This find is returning a collection for some reason NEEDS MORE TESTING
		 */
		$this->model = BlacklistedAddress
			::where('street', $street)
			->where('city', $city)
			->where('state', $state)
			->where('zipcode', $zipcode)
			->first();
		return $this->model;
	}
}