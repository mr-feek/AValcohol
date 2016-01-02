<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/29/15
 * Time: 4:39 AM
 */
class OrderControllerTest extends TestCase
{
	use \Illuminate\Foundation\Testing\DatabaseTransactions;

	public function testCreateOrderWithExistingUser() {
		$this->expectsEvents('App\Events\OrderWasSubmitted');

		$products = $this->getProductsToBuy();
		$address = \App\Models\UserAddress::find(1);
		$user = \App\Models\User::find(1);
		$token = $this->createFakeToken();

		$response = $this->createOrder($products, $address, $user, $token);
		$this->verifyFullOrderInDatabase($response, $products);
	}

	public function testCreateOrderWithoutExistingUser() {
		$this->expectsEvents('App\Events\OrderWasSubmitted');

		$products = $this->getProductsToBuy();
		$address = \App\Models\UserAddress::find(1);
		$user = new \App\Models\User();
		$user->mvp_user = true;
		// don't save user, we'll let endpoint do that
		$token = $this->createFakeToken();

		$response = $this->createOrder($products, $address, $user, $token);

		$this->verifyFullOrderInDatabase($response, $products);
	}

	public function testCreateOrderWithoutExistingAddress() {
		$this->expectsEvents('App\Events\OrderWasSubmitted');

		$products = $this->getProductsToBuy();
		$user = \App\Models\User::find(1);
		// create a fake address (dont attach it to the user, let back end do that. Also don't persist to db)
		$address = factory(\App\Models\UserAddress::class)->make();
		$token = $this->createFakeToken();

		$response = $this->createOrder($products, $address, $user, $token);
		$this->verifyFullOrderInDatabase($response, $products);
	}

	public function testCreateOrderFailsWithInvalidProductID() {
		//$this->expectsEvents('connection.rollingBack');
		// TO DO: figure out how to listen for rolling back event so we actually know no orders were created...

		$products = $this->getProductsToBuy();
		$fake_product = new \App\Models\Product();
		$fake_product->id = 0;
		$products[] = $fake_product;
		$user = \App\Models\User::find(1);
		$token = $this->createFakeToken();
		$address = \App\Models\UserAddress::find(1);

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "Invalid Product ID: 0"]);
	}

	protected function verifyOrderNotCreated() {
		$this->seeJson(['success' => false]);
	}

	protected function getProductsToBuy() {
		return [
			\App\Models\Product::find(1)->toArray(),
			\App\Models\Product::find(2)->toArray(),
			\App\Models\Product::find(3)->toArray()
		];
	}

	/**
	 * @return \Stripe\Token token guaranteed to authenticate
	 */
	protected function createFakeToken() {
		\Stripe\Stripe::setApiKey(Dotenv::findEnvironmentVariable('STRIPE_KEY'));

		return \Stripe\Token::create(array(
			"card" => array(
				"number" => "4242424242424242",
				"exp_month" => 12,
				"exp_year" => 2016,
				"cvc" => "314"
			)
		));
	}

	/**
	 * Verifys order entry is in database with correct amount and ensures all order products were created
	 * @param $response response's content
	 * @param $products array of models
	 */
	protected function verifyFullOrderInDatabase($response, $products) {
		$this->seeJson([
			'success' => true
		]);

		$amount = 0;

		foreach ($products as $product) {
			$amount += $product['price'];
		}

		$this->seeInDatabase('orders', [
			'id' => $response->order->id,
			'amount' => $amount,
			'status' => 'pending',
			'user_id' => $response->order->user_id,
			'user_address_id' => $response->order->user_address_id
		]);

		$this->verifyOrderProductsInDatabase($response->order->id, $products);
	}

	/**
	 * @param $order_id
	 * @param $products array of models
	 */
	protected function verifyOrderProductsInDatabase($order_id, $products) {
		foreach ($products as $product) {
			$this->seeInDatabase('order_product', ['order_id' => $order_id, 'product_id' => $product['id'], 'product_price' => $product['price']]);
		}
	}

	/**
	 * @param $products array
	 * @param $address model
	 * @param $user model
	 * @param $token stripe token
	 * @return mixed response content
	 */
	protected function createOrder($products, $address, $user, $token) {
		$data = [
			'products' => $products,
			'address' => $address->toArray(),
			'user' => $user->toArray(),
			'stripe_token' => $token
		];

		$this->post('/order', $data);

		return json_decode($this->response->getContent());
	}
}