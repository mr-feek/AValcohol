<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:34 PM
 */

namespace App\Models\Repositories;

use App\Models\Entities\Order;
use App\Models\Entities\OrderStatus;
use App\Models\Entities\User;
use App\Models\Entities\UserAddress;
use App\Models\Repositories\Interfaces\OrderInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderInterface
{
	public function __construct(Order $order)
	{
		$this->model = $order;
	}

	/**
	 * Create order
	 * @param User $user
	 * @param UserAddress $address
	 * @param $products
	 * @param $data array with note for now
	 * @return Order
	 */
	public function createOrder(User $user, UserAddress $address, $products, $data) {
		$this->model->amount = 0;
		$this->model->user_id = $user->id;
		$this->model->user_address_id = $address->id;
		$this->model->note = $data['note'];
		$order = $this->model;
		$stripe_token = $data['stripe_token'];

		// start a transaction so that if something is incorrect, no data is saved
		DB::transaction(function() use(&$order, $products, $user, $stripe_token) {
			$order->save(); // save first so that we can attach relations

			$amount = 0;
			foreach ($products as $p) {
				$amount += $p->pivot->sale_price;

				// create order_product record
				$order->products()->attach($p->id, [
					'product_vendor_price' => $p->pivot->vendor_price,
					'product_sale_price' => $p->pivot->sale_price,
					'vendor_id' => $p->pivot->vendor_id
				]);
			}

			// update the order record with the proper price
			$order->amount = $amount;
			$order->save();

			$order->status()->save(new OrderStatus());
		});

		return $this->model;
	}

	public function chargeUserForOrder(User $user, Order $order, $stripe_token) {
		$amount = $order->amount * 100; // charge amount needs to be converted to pennies

		$options = [
			'currency' => 'usd',
			'description' => 'test charge',
			'source' => $stripe_token,
			'receipt_email' => $user->email,
			'metadata' => array(
				'user_id' => $user->id,
				'order_id' => $order->id
			)
		];

		return $user->charge($amount, $options);
	}
}