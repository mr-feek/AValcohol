<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Models\OrderIdentity;
use App\Http\Services\OrderIdentityService;
use Illuminate\Http\Request;

class OrderIdentityController extends Controller
{

	/**
	 * @param Request $request
	 * @param \App\Http\Controllers\OrderIdentityService|OrderIdentityService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws APIException
	 */
	public function createOrder(Request $request, OrderIdentityService $service) {
		$this->validate($request, [
			'photoId' => 'required',
			'signature' => 'required',
			'orderId' => 'required'
		]);

		$orderIdentity = $service->record($request->input());

		return response()->json([
			'success' => true,
			'orderIdentity' => $orderIdentity
		]);
	}
}
