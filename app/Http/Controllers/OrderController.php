<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Models\OrderProduct;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

	/**
	 * @param Request $request
	 * @param \App\Http\Controllers\OrderService|OrderService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws APIException
	 */
	public function createOrder(Request $request, OrderService $service) {
		$this->validate($request, [
			'products' => 'required',
			'user' => 'required',
			'address' => 'required',
			'stripe_token' => 'required',
			'terms_and_conditions' => 'accepted'
		]);

		$order = $service->create($request->input());

		return response()->json([
			'success' => true,
			'order' => $order
		]);
	}
}