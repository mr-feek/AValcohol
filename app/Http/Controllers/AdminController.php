<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\AdminService;
use App\Models\AdminLoginAsVendor;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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

		$this->authorize('get', $orders);

		return response()->json([
			'orders' => $orders,
			'total_count' => $count
		]);
	}

	public function getLoginTokenForVendor(Request $request, $vendorId) {
		$vendor = Vendor::find($vendorId);
		$vendorUser = $vendor->user;
		$token = JWTAuth::fromUser($vendorUser);
		
		AdminLoginAsVendor::create([
			'user_id' => $request->user()->id,
			'vendor_id' => $vendor->id
		]);
		
		return response()->json(['token' => $token]);
	}
}