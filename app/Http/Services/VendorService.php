<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\UserAddressInterface;
use App\Http\Repositories\Interfaces\UserInterface;
use App\Http\Repositories\Interfaces\VendorInterface;
use App\Models\DeliveryZone;

class VendorService extends BaseService
{
	protected $userService;

	/**
	 * @var VendorHoursService
	 */
	private $vendorHoursService;

	public function __construct(VendorInterface $vendorRepo, UserService $userService, VendorHoursService $vendorHoursService)
	{
		$this->repo = $vendorRepo;
		$this->userService = $userService;
		$this->vendorHoursService = $vendorHoursService;
	}

	public function create($data) {
		$user = $this->userService->create($data, false, false);
		$vendor = $this->repo->create($user, $data);
		return $vendor;
	}

	public function getOpenVendorsForAddress($address) {
		$deliveryZoneId = $address['delivery_zone_id'];
		$deliveryZone = DeliveryZone::find($deliveryZoneId);
		
		return $this->vendorHoursService->getOpenVendorsForDeliveryZone($deliveryZone);
	}

	/**
	 * returns _ALL_ (including closed) vendors for the given delivery zone id, so this probably shouldn't be used.
	 * Instead prefer getOpenVendorsForAddress as it filters for only vendors that are currently open.
	 * @param $address array
	 * @return array
	 */
	public function getVendorsForAddress($address) {
		$deliveryZoneId = $address['delivery_zone_id'];
		return $this->repo->getByDeliveryZone($deliveryZoneId);
	}

	/**
	 * @param $vendor array
	 * @return mixed collection
	 */
	public function getProductsForVendor($vendor) {
		$vendor = $this->repo->getById($vendor['id']);
		return $this->repo->getProducts($vendor);
	}

	/**
	 * returns a specific vendor prdouct
	 * @param $vendor_id
	 * @param $product_id
	 * @return mixed
	 */
	public function getProduct($vendor_id, $product_id) {
		$vendor = $this->repo->getById($vendor_id);
		$product = $this->repo->getProduct($vendor, $product_id);
		return $product;
	}

	/**
	 * returns all orders that a vendor has not accepted / rejected yet
	 * @param $vendor
	 */
	public function getPendingOrders($vendor) {
		$vendor = $this->repo->getById($vendor['id']);
		return $this->repo->getAllPendingOrders($vendor);
	}
}