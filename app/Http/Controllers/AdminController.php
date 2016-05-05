<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	/**
	 * Returns all orders that are ready to be picked up from any vendor
	 * @param Request $request
	 * @param AdminService $service
	 * @return mixed
	 */
	public function getOrdersReadyToBePickedUp(Request $request, AdminService $service) {
		$orders = $service->getOrdersReadyToBePickedUp();
		return response()->json(['orders' => $orders]);
	}

	/**
	 * Returns all orders that are currently out for delivery
	 * @param Request $request
	 * @param AdminService $service
	 * @return mixed
	 */
	public function getOrdersOutForDelivery(Request $request, AdminService $service) {
		$orders = $service->getOrdersOutForDelivery();
		return response()->json(['orders' => $orders]);
	}
}