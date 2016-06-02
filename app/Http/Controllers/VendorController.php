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
use Illuminate\Http\Request;

class VendorController extends Controller
{
/*
	public function getAllOrders(Request $request, VendorService $service) {
		// to do: migrate to service
		$orders = Order::with(['products', 'user.profile', 'address'])->get();
		return response()->json(['orders' => $orders]);
	}
*/

	/**
	 * Gets all orders that we have submitted to a vendor, awaiting their response
	 * @param Request $request
	 * @param VendorService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getAllPendingOrders(Request $request, VendorService $service) {
		//$user = JWTAuth::parseToken()->authenticate();
		$user = $request->user();
		$vendor = $user->vendor->toArray();
		$orders = $service->getPendingOrders($vendor); // pull vendor id from token

		if (!$orders->isEmpty()) {
			// TODO: do we only need to check the first one?
			foreach($orders as $order) {
				$this->authorize('vendorGetOrder', $order);
			}
		}

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

	/**
	 * this is a patch method. only the changed attributes will be passed into the request parameters
	 * @param Request $request
	 * @param $orderId
	 * @param VendorService $service
	 * @return mixed
	 * @throws APIException
	 */
	public function updateOrderStatus(Request $request, VendorService $service, $orderId) {
		$data = $request->input();
		$data['order_id'] = $orderId;
		$vendorStatus = $data['vendor_status'];

		if ($vendorStatus === 'accepted') {
			$success = $service->vendorAcceptOrder($data);
		} else if ($vendorStatus === 'rejected') {
			$success = $service->vendorRejectOrder($data);
		} else {
			throw new APIException('incorrect vendor status supplied');
		}

		return response()->json(compact('success'));
	}
}