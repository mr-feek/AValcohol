<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Models\Order;
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
			'stripe_token' => 'required'
		]);

		$order = $service->create($request->input());

		return response()->json([
			'success' => true,
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
			->with(['products', 'user', 'address'])
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

		$order = Order::where('id', $id)->with(['user', 'products', 'address'])->get();

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
