<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

	/**
	 * @param Request $request
	 * 		- user_id
	 * 		- address_id
	 * 		- product_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createOrder(Request $request) {
		$success = false;

		$productId = $request->input('product_id');
		$product = Product::find($productId);

		$userId = $request->input('user_id');
		$user = User::find($userId);

		$addressId = $request->input('address_id');
		$address = UserAddress::find($addressId);

		if ($product && $user && $address) {
			$amount = $product->price;
			$order = new Order();

			$order->amount = $amount;
			$order->status = 'pending'; // TO DO: make this the default db side
			$order->product_id = $productId;
			$order->user_id = $userId;
			$order->user_address_id = $addressId;

			$success = $order->save();
		}

		return response()->json([
			'success' => $success,
			'order' => $order
		]);
	}
}
