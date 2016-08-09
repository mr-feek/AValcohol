<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:37 AM
 */

namespace App\Http\Services;

use App\Exceptions\APIException;
use App\Http\Repositories\Interfaces\BlacklistedAddressInterface;

class BlacklistedAddressService extends BaseService
{
	protected $blacklistedReason = '';

	public function __construct(BlacklistedAddressInterface $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Determines whether or not a given address is blacklisted from being delivered to
	 *
	 * requires the delivery_zone_id to be passed in! as it checks street + delivery zone id
	 * @param $data
	 * @return bool
	 * @throws APIException
	 */
	public function isBlacklisted($data) {
		if (!$data['delivery_zone_id']) {
			throw new APIException('delivery zone id not supplied for checking if an address is blacklisted');
		}

		$model = $this->repo->get($data);

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