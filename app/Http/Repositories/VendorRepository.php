<?php

namespace App\Http\Repositories;

use App\Exceptions\APIException;
use App\Exceptions\NoCollectionResultsAPIException;
use App\Models\User;
use App\Models\Vendor;
use App\Http\Repositories\Interfaces\VendorInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class VendorRepository extends BaseRepository implements VendorInterface
{
	public function __construct(Vendor $vendor)
	{
		$this->model = $vendor;
	}

	/**
	 * @param User $user
	 * @param $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(User $user, $data) {
		$vendor = new Vendor($data);
		return $user->vendor()->save($vendor);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getById($id) {
		return $this->model = Vendor::findOrFail($id);
	}

	/**
	 * This function no removes the vendor_price from the response. We need it in order to calculate tax on the front end
	 * @param Vendor $vendor
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getProducts(Vendor $vendor) {
		$products =  $vendor->products()->get();
		
		/*
		$withoutVendorPrice = $products->each(function($model, $key) {
			unset($model->pivot->vendor_price);
		});
		*/

		return $products;
	}

	/**
	 * @param Vendor $vendor
	 * @param $productId
	 * @return mixed
	 * @throws APIException
	 */
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
		$orders = $vendor->orders()->whereHas('status', function($query) {
			$query->where([
				['vendor_status', 'pending'],
				['charge_authorized', true],
				['charge_captured', false]
			]);
		})->with(['status', 'user.profile', 'products'])->get();
		
		return $orders;
	}

	/**
	 * returns all vendors belonging to this delivery zone
	 * @param $deliveryZoneId
	 * @return Vendor|\Illuminate\Database\Query\Builder
	 */
	public function getByDeliveryZone($deliveryZoneId)
	{
		return $this->model->whereDeliveryZoneId($deliveryZoneId)->get();
	}
}