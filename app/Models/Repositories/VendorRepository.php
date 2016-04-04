<?php

namespace App\Models\Repositories;

use App\Exceptions\APIException;
use App\Models\Entities\Vendor;
use App\Models\Repositories\Interfaces\VendorInterface;

class VendorRepository extends BaseRepository implements VendorInterface
{
	public function __construct(Vendor $vendor)
	{
		$this->model = $vendor;
	}

	public function login($username, $password) {

	}

	public function getById($id) {
		return $this->model = Vendor::findOrFail($id);
	}

	/**
	 * This function removes the vendor_price from the response so that it does
	 * not accidentally get exposed to end users
	 *
	 * to do: this is super slow, can't figure out the eloquent way to do this on db end
	 * @param Vendor $vendor
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getProducts(Vendor $vendor) {
		$products =  $vendor->products()->get();

		$withoutVendorPrice = $products->each(function($model, $key) {
			unset($model->pivot->vendor_price);
		});

		return $withoutVendorPrice;
	}

	public function getProduct(Vendor $vendor, $productId)
	{
		$product = $vendor->products()->where('product_id', $productId)->first();
		if (!$product) {
			throw new APIException('The specified product was not found');
		}
		return $product;
	}

	/**
	 * This query can be optimized by querying order table directly
	 * @param Vendor $vendor
	 * @return mixed
	 */
	public function getAllPendingOrders(Vendor $vendor)
	{
		$orders = $vendor->orders()->with(['user.profile', 'products', 'status' => function($query) {
			$query->where('vendor_status', 'pending');
		}])->get();

		return $orders;
	}
}