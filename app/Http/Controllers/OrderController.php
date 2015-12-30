<?php

namespace App\Http\Controllers;

use App\Events\OrderWasSubmitted;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class OrderController extends Controller
{

	/**
	 * TO D0: support for creating a new address and user
	 * @param Request $request
	 * 		- user_id
	 * 		- address_id
	 * 		- product_id
	 * 		- stripe_token
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createOrder(Request $request) {
		$success = false;

		$this->validate($request, [
			'products' => 'required',
			'user' => 'required',
			'address' => 'required',
			'stripe_token' => 'required'
		]);

		$user_id = $request->input('user.id');
		$user = User::find($user_id);

		$address_id = $request->input('address.id');
		$address = UserAddress::find($address_id);

		$stripe_token = $request->input('stripe_token');

		$products = $request->input('products');

		if ($user && $address) {
			// to do: TRANSACTION for all this!!
			$order = new Order();
			$order->amount = 0;
			$order->status = 'pending'; // TO DO: make this the default db side
			$order->user_id = $user_id;
			$order->user_address_id = $address_id;
			$order->save();

			$amount = 0;
			foreach ($products as $p) {
				$product = Product::find($p->id);

				if (!$product) {
					// not an actual product id...
					dd('something went wrong, not an actual product. to do: rollback transaction so order isnt saved');
				}

				$amount += $product->price;

				// create order_product record
				$order->products()->attach($product->id, ['product_price' => $product->price]);
			}

			// update the order record with the proper price
			$order->amount = $amount;
			$order->save();

			// lets charge em
			$charge = UserController::charge($user->id, $order->id, $stripe_token);

			$success = true; // temp

			// notify pusher etc
			Event::fire(new OrderWasSubmitted($order));
		} else {
			dd('user or address not found...');
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
