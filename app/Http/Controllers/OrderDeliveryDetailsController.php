<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Models\OrderIdentity;
use App\Http\Services\OrderIdentityService;
use Illuminate\Http\Request;

class OrderDeliveryDetailsController extends Controller
{

	/**
	 * @param Request $request
	 * @param \App\Http\Controllers\OrderIdentityService|OrderIdentityService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws APIException
	 */
	public function create(Request $request, OrderIdentityService $service) {
		$this->validate($request, [
			'photoData' => 'required',
			'signatureSVGData' => 'required',
			'order_id' => 'required'
		]);

		$orderDeliveryDetails = $service->record($request->input());

		return response()->json([
			'success' => true,
			'delivery_details' => $orderDeliveryDetails
		]);
	}
}
