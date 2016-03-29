<?php

namespace App\Http\Controllers;
use App\Models\Entities\Product;
use App\Models\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	/**
	 * Returns all VendorProducts available for this address to purchase
	 *
	 * Requires address_id to be sent to figure out what vendors can deliver.
	 *
	 * to do: probably set up zones and use that as get parameter
	 * @param Request $request
	 * @param ProductService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function getAllProductsForAddress(Request $request, ProductService $service) {
		$this->validate($request, [
			'address_id' => 'required'
		]);

		$address = ['id' => $request->address_id];

		$products = $service->getAllProductsForAddress($address);

		return response()->json([
			'products' => $products
		]);
	}

	/*
	 * Not needed for beta (not implemented)
	 */
	public function getAllFeatured(Request $request, ProductService $service) {
		$this->validate($request, [
			'address_id' => 'required'
		]);

		$address = ['id' => $request->address_id];

		$products = $service->getAllFeaturedProductsForAddress($address);

		return response()->json([
			'products' => $products
		]);
	}
}
