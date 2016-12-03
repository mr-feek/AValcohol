<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:57 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\ProductInterface;
use App\Http\Repositories\Interfaces\VendorInterface;
use App\Http\Repositories\VendorRepository;

class ProductService extends BaseService
{
	protected $vendorService;

	public function __construct(ProductInterface $productRepo, VendorService $vendorService)
	{
		$this->repo = $productRepo;
		$this->vendorService = $vendorService;
	}

	/**
	 * @param array of objects $data with id, everything else is ignored
	 * @return array
	 */
	public function getAll($data) {
		$collection = [];
		foreach ($data as $productData) {
			$collection[] = $this->repo->getById($productData->id);
		}
		return $collection;
	}

	/**
	 * returns all products that are in stock at vendors for the given address
	 *
	 * TODO: Filter to not have duplicate products from different vendors
	 * @param $data
	 * @return array $products
	 *
	 * @internal param array $address with id
	 *
	 */
	public function getAllProductsForAddress($data) {
		if (isset($data['includeClosed'])) {
			$vendors = $this->vendorService->getVendorsForAddress($data);
		} else {
			$vendors = $this->vendorService->getOpenVendorsForAddress($data);
		}

		//$vendors = $this->vendorService->getVendorsForAddress($address);

		$products = [];
		foreach($vendors as $vendor) {
			$vendorProducts = $this->vendorService->getProductsForVendor($vendor->toArray())->toArray();
			$products = array_merge($products, $vendorProducts);
		}

		return $products;
	}

	/*
	 * NOT DONE. don't need for beta
	 *
	public function getAllFeaturedProductsForAddress($address) {
		$vendors = $this->vendorService->getVendorsForAddress($address);

		$products = [];
		foreach($vendors as $vendor) {
			$vendorProducts = $this->vendorService->getProductsForVendor($vendor->toArray())->toArray();
			$products = array_merge($products, $vendorProducts);
		}

		return $products;
	}
	*/
}
