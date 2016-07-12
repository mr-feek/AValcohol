<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 11:47 AM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\SiteStatusInterface;
use App\Models\DeliveryZone;

class SiteStatusService extends BaseService
{

	/**
	 * @var VendorHoursService
	 */
	private $vendorHoursService;

	public function __construct(SiteStatusInterface $repo, VendorHoursService $vendorHoursService)
	{
		$this->repo = $repo;
		$this->vendorHoursService = $vendorHoursService;
	}

	/**
	 * @return mixed
	 */
	public function get() {
		return $this->repo->get();
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function setForceOffline(array $data) {
		return $this->repo->closeStore($data['admin_force_offline']);
	}

	/**
	 * determines whether or not the store is open for orders.
	 * Admin CANNOT force the online store to be open.
	 *
	 * @param array $data
	 * @return bool
	 */
	public function isOpenNow(array $data) {
		if ($this->repo->devForcedOpen()) {
			return true;
		}

		if ($this->repo->checkIfAdminHasClosedStore()) {
			return false;
		}

		// I don't think this will ever actually be included? this method is called
		// by store open before an address is collected on the front end.
		
		if (array_key_exists('location', $data)) {
			$deliveryZone = DeliveryZone::find(1); //todo

			return $this->vendorHoursService->areOpenVendorsInDeliveryZone($deliveryZone);
		}

		return true;
	}

	public function reasonForStoreClosure() {
		return $this->repo->closedMessage;
	}
}