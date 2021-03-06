<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:34 PM
 */

namespace App\Http\Repositories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserAddress;
use App\Http\Repositories\Interfaces\OrderInterface;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Error\Card;

class OrderRepository extends BaseRepository implements OrderInterface
{
	public function __construct(Order $order)
	{
		$this->model = $order;
	}

	/**
	 * @param $order_id
	 * @return Order
	 */
	public function getByOrderId($order_id) {
		return $this->model->findOrFail($order_id);
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
		$this->model->user_id = $user->id;
		$this->model->user_address_id = $address->id;
		$this->model->note = $data['note'];
		$this->model->terms_and_conditions = $data['terms_and_conditions']; // should be true due to controller validation
		$this->model->vendor_id = $products[0]->pivot->vendor_id; // TEMP!!! to do: remove vendor id from orders table
		$stripe_token = $data['stripe_token'];

		// don't need to use a transaction here because calling code (service) is wrapped in a transaction
		$this->model->save(); // save first so that we can attach relations
		$this->model->addMultipleProducts($products)->calculateDeliveryFee()->save();
		$this->model->status()->save(new OrderStatus());

		return $this->model;
	}

	/**
	 * @deprecated now we authorize then capture when vendor accepts. this charges user immediately
	 * @param User $user
	 * @param Order $order
	 * @param $stripe_token
	 * @return \Stripe\Charge
	 *
	public function chargeUserForOrder(User $user, Order $order, $stripe_token) {
		if (env('OFFLINE_MODE') === true) {
			return;
		}

		$amount = $order->full_charge_amount * 100; // charge amount needs to be converted to pennies

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
	} */

	/**
	 * @param Order $order
	 * @param $stripe_token
	 * @return Order
	 * @throws Card error to bubble up
	 */
	public function authorizeChargeOnCard(Order $order, $stripe_token)
	{
		$amount = $order->calculateChargeAmountForProcessor();

		$options = [
			'currency' => 'usd',
			'description' => 'test charge',
			'source' => $stripe_token,
			'receipt_email' => $order->user->email,
			'capture' => false, // JUST AUTHORIZE THE AMOUNT! will be charged when vendor accepts
			'metadata' => array(
				'user_id' => $order->user->id,
				'order_id' => $order->id
			)
		];

		if (env('OFFLINE_MODE') === true) {
			$charge = new \stdClass();
			$charge->id = 'offline-mode-set';
		} else {
			$charge = $order->user->charge($amount, $options);
		}

		$order->status()->update([
			'charge_id' => $charge->id,
			'charge_authorized' => true
		]);
		$order->save();

		return $order;
	}

	/**
	 * captures a pre existing authorized charge
	 * @param Order $order
	 * @return mixed
	 */
	public function capturePreExistingCharge(Order $order) {
		$chargeID = $order->status->charge_id;
		$charge = Charge::retrieve($chargeID);
		$captured = $charge->capture();
		return $captured;
	}

	/**
	 * deletes a pre existing authorized charge
	 * @param Order $order
	 * @return mixed
	 */
	public function deletePreExistingCharge(Order $order) {
		$chargeID = $order->status->charge_id;
		$charge = Charge::retrieve($chargeID);
		$captured = $charge->refund();
		return $captured;
	}
}