<?php

namespace App\Http\Controllers;

use App\Events\OrderWasSubmitted;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class OrderController extends Controller
{

	/**
	 * TO D0: support for creating a new address
	 * support for ordering multiple products
	 * send token to stripe
	 * create charge
	 * @param Request $request
	 * 		- user_id
	 * 		- address_id
	 * 		- product_id
	 * 		- stripe_token
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createOrder(Request $request) {
		$this->validate($request, [
			'products.id' => 'required',
			'user.id' => 'required',
			'address.id' => 'required',
			'stripe_token' => 'required'
		]);

		$success = false;

		$productId = $request->input('products.id'); // temp. only supports 1 product for now
		$product = Product::find($productId);

		$userId = $request->input('user.id');
		$user = User::find($userId);

		$addressId = $request->input('address.id');
		$address = UserAddress::find($addressId);

		$stripe_token = $request->input('stripe_token');

		if ($product && $user && $address) {
			$amount = $product->price;
			$order = new Order();

			$order->amount = $amount;
			$order->status = 'pending'; // TO DO: make this the default db side
			$order->product_id = $productId;
			$order->user_id = $userId;
			$order->user_address_id = $addressId;

			$success = $order->save();

			if ($success) {
				// lets charge em
				$success = UserController::charge($user->id, $order->id, $stripe_token);

				// notify pusher etc
				Event::fire(new OrderWasSubmitted($order));
			}
		}

		return response()->json([
			'success' => $success,
			'order' => $order
		]);
	}

	public function getAllPendingAndOutForDelivery() {
		/*
		$orders = DB::table('orders')
			->where('status', 'pending')
			->orWhere('status', 'out-for-delivery')
			->join('products', 'orders.product_id', '=', 'products.id')
			->join('users', 'orders.user_id', '=', 'users.id')
			->join('user_addresses', 'orders.user_address_id', '=', 'user_addresses.id')
			->get();
*/
		$orders = Order::
			where('status', 'pending')
			->orWhere('status', 'out-for-delivery')
			->with(['product', 'user', 'address'])
			->get();

		return response()->json([
			'orders' => $orders
		]);
	}

	/**
	 * @param Request $request
	 * 		- id (order id)
	 * @param int id
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * fetches info regarding order, user, and address to be delivered to
	 */
	public function getFullOrderInfo(Request $request, $id) {
		/*
		$order = Order::
			where('orders.id', '=', $id)
			->join('products', 'orders.product_id', '=', 'products.id')
			->join('users', 'orders.user_id', '=', 'users.id')
			->join('user_addresses', 'orders.user_address_id', '=', 'user_addresses.id')
			->get();
*/

		$order = Order::where('id', $id)->with(['user', 'product', 'address'])->get();

		return response()->json([
			'order' => $order
		]);
	}

	/**
	 * @param Request $request
	 * 		- order_id
	 * 		- status
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function updateStatus(Request $request) {
		$statuses = Order::getStatusKeys();

		$this->validate($request, [
			'order_id' => 'required',
			'status' => 'required|in:' . implode(',', $statuses)
		]);

		$order_id = $request->input('order_id');
		$status = $request->input('status');

		$order = Order::find($order_id);
		$order->status = $status;
		$order->save();

		return response()->json([
			'order_id' => $order_id,
			'status' => $order->status
		]);
	}

}
