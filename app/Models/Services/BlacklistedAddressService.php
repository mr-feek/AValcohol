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
	protected $blacklistedReason = '';

	public function __construct(BlacklistedAddressInterface $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Determines whether or not a given address is blacklisted from being delivered to
	 * @param $data
	 * @return bool
	 */
	public function isBlacklisted($data) {
		$model = $this->repo->get($data['street'], $data['city'], $data['state'], $data['zipcode']);

		if ($model) {
			$this->blacklistedReason = $model->getReason();
			return true;
		}

		return false;
	}

	/**
	 * Returns the reason for an address being blacklisted (can only be called after calling isBlacklisted)
	 * @return string
	 */
	public function getReason() {
		return $this->blacklistedReason;
	}
}