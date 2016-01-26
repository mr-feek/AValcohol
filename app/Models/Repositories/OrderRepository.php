<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:34 PM
 */

namespace App\Models\Repositories;

use App\Models\Entities\Order;
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
	 * Create order and charge user should probably be split into two methods and controlled
	 * by the service, but since it needs to be a transaction im just doing it all here for now
	 * @param User $user
	 * @param UserAddress $address
	 * @param $products
	 * @param $data
	 */
	public function createOrderAndChargeUser(User $user, UserAddress $address, $products, $data) {
		$this->model->amount = 0;
		$this->model->status = 'pending'; // TO DO: make this the default db side
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
				$amount += $p->price;
				// create order_product record
				$order->products()->attach($p->id, ['product_price' => $p->price]);
			}

			// update the order record with the proper price
			$order->amount = $amount;
			$order->save();

			// lets charge em
			$this->chargeUserForOrder($user, $order, $stripe_token);
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