<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Models\Services;

use App\Exceptions\InvalidPermissionException;
use App\Models\Repositories\Interfaces\UserAddressInterface;
use App\Exceptions\APIException;

class UserAddressService extends BaseService
{
	protected $blacklistedService;
	protected $userService;

	public function __construct(UserAddressInterface $repo, BlacklistedAddressService $blacklistedService, UserService $userService)
	{
		$this->repo = $repo;
		$this->blacklistedService = $blacklistedService;
		$this->userService = $userService;
	}

	public function get($id) {
		return $this->repo->getById($id);
	}

	public function update($data) {
		$address = $this->get($data['id']);

		if (!$this->repo->userCanUpdateAddress($address, $data['user_id'])) {
			throw new InvalidPermissionException();
		}

		return $this->repo->update($address, $data);
	}

	public function create($data) {
		if (!$this->canDeliverToAddress($data)) {
			throw new APIException('message will go here');
		}

		$user = $this->userService->getUser($data['user']['id']);

		return $this->repo->create($user, $data);
	}

	public function canDeliverToAddress($data) {
		/*
		$longitude = $data['long'];
		$latitude = $data['lat'];

		if (!$this->repo->isInDeliveryZone($longitude, $latitude)) {
			throw new APIException($this->repo->cannotDeliverMessage);
		}
		*/

		if ($this->blacklistedService->isBlacklisted($data)) {
			throw new APIException($this->blacklistedService->getReason());
		}

		return true;
	}
}