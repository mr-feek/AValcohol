<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/13/16
 * Time: 4:22 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderStatusInterface;

class OrderStatusService extends BaseService
{
	public function __construct(OrderStatusInterface $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function vendorRejectOrder(array $data) {
		return $this->repo->update($data);
		// to do: delete user's charge authorization
		// to do: email customer saying charge was declined for whatever reason
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function vendorAcceptOrder(array $data) {
		return $this->repo->update($data);
		// to do: capture users charge authorization
		// to do: email customer with receipt and that vendor accepted order
	}

	public function driverPickUpOrder(array $data) {
		return $this->repo->update($data);
	}

	public function update(array $data) {
		$this->repo->update($data);
	}
}