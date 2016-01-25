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

	public function get($data)
	{
		return BlacklistedAddress::find($data);
	}
}