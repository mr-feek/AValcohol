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

/**
 * This controller services all requests made from the admin for now
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
	public function getOrders(Request $request, AdminService $service) {
		$orders = $service->getOrders($request->input());
		$count = $service->getTotalNumberOfOrdersPlacedToDate();

		return response()->json([
			'orders' => $orders,
			'total_count' => $count
		]);
	}
}