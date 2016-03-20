<?php

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\VendorInterface;

class VendorService extends BaseService
{
	public function __construct(VendorInterface $vendorRepo)
	{
		$this->repo = $vendorRepo;
	}

	public function login() {

	}
}