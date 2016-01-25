<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:37 AM
 */

namespace App\Models\Services;


use App\Models\Repositories\Interfaces\BlacklistedAddressInterface;

class BlacklistedAddressService extends BaseService
{
	public function __construct(BlacklistedAddressInterface $repo)
	{
		$this->repo = $repo;
	}

	public function isBlacklisted($data) {
		$model = $this->repo->get($data);
		return $model ? true : false;
	}
}