<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:46 PM
 */

namespace App\Http\Controllers;


use App\Models\Entities\Order;
use App\Models\Services\VendorService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VendorController extends Controller
{
	/**
	 * Handle a login request to the application
	 * @param Request $request
	 * @param VendorService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login(Request $request, VendorService $service) {
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required'
		]);

		$credentials = $request->only('email', 'password');

		try {
			if (!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		}
		catch (JWTException $e)
        {
			// something went wrong whilst attempting to encode the token
			return response()->json(['error' => 'could_not_create_token'], 500);
		}

        // all good so return the token
        return response()->json(compact('token'));
	}

	public function getAllOrders(Request $request, VendorService $service) {
		// TO DO
		$orders = Order::with(['products', 'user.profile', 'address'])->get();
		return response()->json(['orders' => $orders]);
	}
}