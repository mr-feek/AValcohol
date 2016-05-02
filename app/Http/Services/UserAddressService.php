<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Http\Services;

use App\Exceptions\InvalidPermissionException;
use App\Http\Repositories\Interfaces\UserAddressInterface;
use App\Exceptions\APIException;
use App\Models\DeliveryZone\Point;
use App\Models\DeliveryZone;

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
		$this->canDeliverToAddress($data); // will throw exception if cannot. bad design but whatever.

		$user = $this->userService->getUser($data['user']['id']);
		$data['delivery_zone_id'] = $this->getDeliveryZoneID($data);
		return $this->repo->create($user, $data);
	}

	/**
	 * checks if any vendors for the given location (ignores if there is already a delivery zone id for now)
	 * checks if address is blacklisted
	 * @param $data
	 * @return bool
	 * @throws APIException
	 */
	public function canDeliverToAddress($data) {
		$longitude = $data['location']['longitude'];
		$latitude = $data['location']['latitude'];

		if (!$this->isInDeliveryZone($data)) {
			throw new APIException($this->repo->cannotDeliverMessage);
		}

		if ($this->blacklistedService->isBlacklisted($data)) {
			throw new APIException($this->blacklistedService->getReason());
		}

		return true;
	}

	/**
	 * determines if long / lat is in a delivery zone
	 * @param $data
	 * @return bool
	 */
	private function isInDeliveryZone($data) {
		if ($this->getDeliveryZoneID($data) != null) {
			return true;
		}

		return false;
	}

	/**
	 * returns the delivery zone for the supplied [NESTED UNDER LOCATION KEY] longitude and latitude
	 * @param $data
	 * @return the id of the available delivery zone
	 */
	public function getDeliveryZoneID($data) {
		$point = new Point($data['location']['latitude'], $data['location']['longitude']);
		$zones = DeliveryZone::getZonesContainingPoint($point);
		// this should only ever be one zone.
		$filtered = $zones->where('name', 'State College'); // default to state college instead of rando ones for testing purposes
		if ($filtered->count() >= 1) {
			return $filtered->first()->id;
		}
		return $zones->first()->id;
	}
}