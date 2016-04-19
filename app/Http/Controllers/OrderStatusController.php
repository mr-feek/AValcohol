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
	 * @param Request $request
	 * @param OrderStatusService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function update(Request $request, OrderStatusService $service) {
		$this->validate($request, [
			'order_id' => 'required'
		]);

		$orderStatus = $service->update($request->input());

		return response()->json([
			'order_status' => $orderStatus
		]);
	}
}