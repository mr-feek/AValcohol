<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/29/15
 * Time: 4:39 AM
 */
class OrderControllerTest extends TestCase
{
	public function testCreateOrder() {
		//$this->expectsEvents('App\Events\OrderWasSubmitted');

		$product1 = \App\Models\Product::find(1);
		$product2 = \App\Models\Product::find(2);
		$product3 = \App\Models\Product::find(3);

		$products = [
			$product1,
			$product2,
			$product3
		];

		$address = \App\Models\UserAddress::find(1);

		$user = \App\Models\User::find(1);

		\Stripe\Stripe::setApiKey(Dotenv::findEnvironmentVariable('STRIPE_KEY'));

		$token = \Stripe\Token::create(array(
			"card" => array(
				"number" => "4242424242424242",
				"exp_month" => 12,
				"exp_year" => 2016,
				"cvc" => "314"
			)
		));

		$data = [
			'products' => $products,
			'address' => $address->toArray(),
			'user' => $user->toArray(),
			'stripe_token' => $token
		];

		$this->post('/order', $data)
				->seeJson([
					'success' => true
				]);

		$response = json_decode($this->response->getContent());

		$order_id = $response->order->id;

		$this->seeInDatabase('order_product', ['order_id' => $order_id, 'product_id' => 1, 'product_price' => $product1->price]);
		$this->seeInDatabase('order_product', ['order_id' => $order_id, 'product_id' => 2, 'product_price' => $product2->price]);
		$this->seeInDatabase('order_product', ['order_id' => $order_id, 'product_id' => 3, 'product_price' => $product3->price]);

		$amount = $product1->price + $product2->price + $product3->price;
		$this->seeInDatabase('orders', ['id' => $order_id, 'amount' => $amount]);
	}
}