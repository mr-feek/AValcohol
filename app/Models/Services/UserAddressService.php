<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\UserAddressInterface;

class UserAddressService extends BaseService
{
	protected $blacklistedService;

	public function __construct(UserAddressInterface $repo, BlacklistedAddressService $blacklistedService)
	{
		$this->repo = $repo;
		$this->blacklistedService = $blacklistedService;
	}

	public function get($id) {
		return $this->repo->getById($id);
	}

	public function update($data) {
		$address = $this->get($data['id']);

		if (!$this->repo->userCanUpdateAddress($address, $data['user']['id'])) {
			throw new APIException('invalid permissions.');
		}

		return $this->repo->update($data);
	}

	public function create($data) {
		if (!$this->canDeliverToAddress($data)) {
			// to do: get message
			throw new APIException('message will go here');
		}
		return $this->repo->create($data);
	}

	public function canDeliverToAddress($data) {
		$longitude = $data['long'];
		$latitude = $data['lat'];

		if (!$this->repo->isInDeliveryZone($longitude, $latitude)) {
			// to do
			throw new APIException('message will go here');
		}

		if ($this->blacklistedService->isBlacklisted($data)) {
			// to do
			throw new APIException('message will go here');
		}
	}
}