<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/13/16
 * Time: 4:21 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\OrderStatusService;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
	/**
	 * this is a patch method. only the changed attributes will be passed into the request parameters
	 * @param Request $request
	 * @param VendorService|OrderStatusService $service
	 * @param $orderId
	 * @return mixed
	 * @throws APIException
	 */
	public function updateOrderStatus(Request $request, OrderStatusService $service, $orderId) {
		$data = $request->input();
		$data['order_id'] = $orderId;

		if ($vendorStatus = $request->input('vendor_status')) {
			if ($vendorStatus === 'accepted') {
				$success = $service->vendorAcceptOrder($data);
			} else if ($vendorStatus === 'rejected') {
				$success = $service->vendorRejectOrder($data);
			} else {
				throw new APIException('incorrect vendor status supplied');
			}
		}

		if ($deliveryStatus = $request->input('delivery_status')) {
			if ($deliveryStatus === 'out-for-delivery') {
				$success = $service->driverPickupOrder($data);
			} else {
				throw new APIException('incorrect delivery status supplied');
			}
		}

		return response()->json(compact('success'));
	}
}