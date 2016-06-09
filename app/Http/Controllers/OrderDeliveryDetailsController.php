<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Http\Services\OrderDeliveryDetailsService;
use App\Models\OrderDeliveryDetail;
use App\Models\OrderIdentity;
use Illuminate\Http\Request;

class OrderDeliveryDetailsController extends Controller
{
	/**
	 * @param Request $request
	 * @param OrderIdentityService|OrderDeliveryDetailsService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function create(Request $request, OrderDeliveryDetailsService $service) {
		$this->validate($request, [
			'photoData' => 'required',
			'signature' => 'required', // svg data
			'order_id' => 'required'
		]);

		$signatureData = $request->input('signature');

		// check for valid base64
		$encodedData = explode(',', $signatureData)[1];
		if (base64_encode(base64_decode($encodedData, true)) !== $encodedData){
			return response()->json([
				'success' => false,
				'message' => 'Did not receive valid base 64 encoded data'
			]);
		}

		$orderDeliveryDetails = $service->create($request->input());

		return response()->json([
			'success' => true,
			'delivery_details' => $orderDeliveryDetails
		]);
	}

	public function get(Request $request, OrderDeliveryDetailsService $service, $id) {
		$orderDeliveryDetails = $service->get($id);

		return response()->json([
			'success' => true,
			'delivery_details' => $orderDeliveryDetails
		]);
	}
}
