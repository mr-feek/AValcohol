<?php

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\UserAddressInterface;
use App\Models\Repositories\Interfaces\VendorInterface;

class VendorService extends BaseService
{
	protected $addressRepo;

	public function __construct(VendorInterface $vendorRepo, UserAddressInterface $addressRepo)
	{
		$this->repo = $vendorRepo;
		$this->addressRepo = $addressRepo;
	}

	public function login() {

	}

	/**
	 * @param $address array
	 * @return array
	 */
	public function getVendorsForAddress($address) {
		$address = $this->addressRepo->getById($address['id']);

		// to do. for now just returning vendor with id 1
		return [$this->repo->getById(1)];
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
	 * @param $vendorProduct array with vendor id and product id
	 * @return mixed
	 */
	public function getProduct($vendorProduct) {
		$vendor = $this->repo->getById($vendorProduct['vendor_id']);
		$product = $this->repo->getProduct($vendor, $vendorProduct['product_id']);
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