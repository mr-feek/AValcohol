<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 11:47 AM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\SiteStatusInterface;

class SiteStatusService extends BaseService
{

	public function __construct(SiteStatusInterface $repo)
	{
		$this->repo = $repo;
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
	 * determines whether or not the store is open for orders. Admin CANNOT
	 * force the online store to be open if there are no open vendors
	 * @return bool
	 */
	public function isOpenNow() {
		if ($this->repo->devForcedOpen()) {
			return true;
		}

		if ($this->repo->checkIfAdminHasClosedStore()) {
			return false;
		}

		return $this->repo->checkDeliveryHours();
	}

	public function reasonForStoreClosure() {
		return $this->repo->closedMessage;
	}
}