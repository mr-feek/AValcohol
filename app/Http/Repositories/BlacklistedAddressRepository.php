<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:36 AM
 */

namespace App\Http\Repositories;

use App\Models\BlacklistedAddress;
use App\Http\Repositories\Interfaces\BlacklistedAddressInterface;

class BlacklistedAddressRepository extends BaseRepository implements BlacklistedAddressInterface
{
	public function __construct(BlacklistedAddress $address)
	{
		$this->model = $address;
	}

	public function get(array $data)
	{
		$this->model = BlacklistedAddress::where([
			'street' => $data['street'],
			'delivery_zone_id' => $data['delivery_zone_id']
		])->first();

		return $this->model;
	}
}