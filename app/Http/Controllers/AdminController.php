<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use App\Http\Services\AdminService;

class AdminController extends Controller
{
	public function getOrdersReadyToBePickedUp(Request $request, AdminService $service) {
		$orders = $service->getOrdersReadyToBePickedUp();
		return response()->json(['orders' => $orders]);
	}

	public function getOrdersOutForDelivery(Request $request, AdminService $service) {
		$orders = $service->getOrdersOutForDelivery();
		return response()->json(['orders' => $orders]);
	}
}