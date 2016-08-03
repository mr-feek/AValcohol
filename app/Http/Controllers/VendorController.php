<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:46 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Http\Services\VendorService;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
	/**
	 * to do: migrate to filters
	 * Gets all orders that we have submitted to a vendor, awaiting their response
	 * @param Request $request
	 * @param VendorService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getAllPendingOrders(Request $request, VendorService $service) {
		$user = $request->user();
		$vendor = $user->vendor->toArray();
		$orders = $service->getPendingOrders($vendor); // pull vendor id from token

		$this->authorize('get', $orders);

		return response()->json(['orders' => $orders]);
	}

	/**
	 * @param Request $request
	 * @param VendorService $service
	 * @return mixed
	 */
	public function create(Request $request, VendorService $service) {
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
			'name' => 'required',
			'address' => 'required',
			'phone_number' => 'required',
			'delivery_zone_id' => 'required',
		]);

		$vendor = $service->create($request->input());

		return response()->json(['vendor' => $vendor]);
	}

	/**
	 * this call happens after they logged in, so just parse from token and return
	 * @param Request $request
	 */
	public function get(Request $request) {
		$user = $request->user();
		return response()->json(['vendor' => $user->vendor]);
	}
	
	public function getById(Request $request, $id) {
		$vendor = Vendor::with('hours')->findOrFail($id);
		return response()->json(['vendor' => $vendor]);
	}

	public function getProduct(Request $request, VendorService $service, $vendorId, $productId) {
		$product = $service->getProduct($vendorId, $productId);
		return response()->json([
			'product' => $product
		]);
	}
}