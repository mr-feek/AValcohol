<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:57 PM
 */

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\ProductInterface;
use App\Models\Repositories\Interfaces\VendorInterface;
use App\Models\Repositories\VendorRepository;

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
	 * TO DO: Filter to not have duplicate products from different vendors
	 * @param $address array with id
	 * @return $products
	 *
	 */
	public function getAllProductsForAddress($address) {
		$vendors = $this->vendorService->getVendorsForAddress($address);

		$products = [];
		foreach($vendors as $vendor) {
			$vendorProducts = $this->vendorService->getProductsForVendor($vendor->toArray())->toArray();
			$products = array_merge($products, $vendorProducts);
		}

		return $products;
	}
}