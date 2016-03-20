<?php

namespace App\Models\Repositories;

use App\Models\Entities\Vendor;
use App\Models\Repositories\Interfaces\VendorInterface;

class VendorRepository extends BaseRepository implements VendorInterface
{

	public function __construct(Vendor $vendor)
	{
		$this->model = $vendor;
	}

	public function login($username, $password) {

	}
}