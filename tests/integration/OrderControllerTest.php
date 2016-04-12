<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/29/15
 * Time: 4:39 AM
 */

use App\Models\Entities\User;
use App\Models\Entities\UserAddress;
use App\Models\Entities\Product;

class OrderControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function testCreateOrderWithExistingUser() {
		$this->withoutMiddleware();
		$this->expectsEvents('App\Events\OrderWasSubmitted');

		$products = $this->getProductsToBuy();
		$address = $this->getAddress();
		$user = User::find(1);
		$token = $this->createFakeToken();

		$response = $this->createOrder($products, $address, $user, $token);

		$this->verifyFullOrderInDatabase($response, $products);
	}

	public function testCreateOrderFailsWithInvalidUserID() {
		$this->withoutMiddleware();
		$products = $this->getProductsToBuy();
		$address = $this->getAddress();

		// make user with fake id
		$user = new User();
		$user->id = 'fakeID';

		$token = $this->createFakeToken();

		$this->createOrder($products, $address, $user, $token);

		$this->verifyOrderNotCreated();

		$this->seeJson([
			'message' => 'No query results for model [App\\Models\\Entities\\User].'
		]);
	}

	public function testCreateOrderFailsWithInvalidUserAddressID() {
		$this->withoutMiddleware();
		$products = $this->getProductsToBuy();

		// fake address..
		$address = new UserAddress();
		$address->id = 'fakeID';

		$user = User::find(1);
		$token = $this->createFakeToken();

		$this->createOrder($products, $address, $user, $token);

		$this->verifyOrderNotCreated();

		$this->seeJson([
			'message' => 'No query results for model [App\\Models\\Entities\\UserAddress].'
		]);
	}

	public function testCreateOrderFailsWithInvalidProductID() {
		$this->withoutMiddleware();
		//$this->expectsEvents('connection.rollingBack');
		// TO DO: figure out how to listen for rolling back event so we actually know no orders were created...

		$products = $this->getProductsToBuy();
		$products[0]->id = 0;
		$user = User::find(1);
		$token = $this->createFakeToken();
		$address = $this->getAddress();

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "The specified product was not found"]);

		// now lets do the same with a bad vendor id
		$products = $this->getProductsToBuy();
		$fake_product = new Product();
		$fake_product->id = 1;
		$fake_product->vendor_id = 0;
		$products[] = $fake_product;

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "No query results for model [App\\Models\\Entities\\Vendor]."]);
	}

	public function testCreateOrderWithNote() {
		$this->withoutMiddleware();
		$this->expectsEvents('App\Events\OrderWasSubmitted');

		$products = $this->getProductsToBuy();
		$address = $this->getAddress();
		$user = User::find(1);
		$token = $this->createFakeToken();
		$note = 'noteeee';

		$response = $this->createOrder($products, $address, $user, $token, $note);
		$this->verifyFullOrderInDatabase($response, $products);

		$this->seeInDatabase('orders', [
			'id' => $response->order->id,
			'note' => $note
		]);
	}

	/*
	public function testCannotCreateOrderIfCannotDeliverToAddress() {
		$products = $this->getProductsToBuy();
		$user = \App\Models\User::find(1);
		$token = $this->createFakeToken();
		$zip = 11111;
		$address = factory(\App\Models\Entities\UserAddress::class)->create(['zipcode' => $zip, 'user_id' => 1]);

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "We're sorry, but at this time we can only deliver to the 16801 area"]);
	}
	*/

	protected function verifyOrderNotCreated() {
		$this->seeJson(['success' => false]);
	}

	protected function getProductsToBuy() {
		$vendor = \App\Models\Entities\Vendor::find(1);
		$products = $vendor->products()->take(3)->get();
		return $products;
	}

	/**
	 * @return UserAddress model
	 */
	protected function getAddress() {
		return factory(UserAddress::class)->create(['zipcode' => 16801, 'user_id' => 1]);
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
			$amount += $product->pivot->sale_price;
		}

		$this->seeInDatabase('orders', [
			'id' => $response->order->id,
			'amount' => $amount,
			'user_id' => $response->order->user_id,
			'user_address_id' => $response->order->user_address_id
		]);

		// ensure default order statuses created
		$this->seeInDatabase('order_statuses', [
			'order_id' => $response->order->id,
			'vendor_status' => 'pending',
			'delivery_status' => 'pending',
			'charge_authorized' => 1,
			'charge_captured' => 0
		]);

		// for ensuring there is a charge id
		$order = \App\Models\Entities\Order::find($response->order->id);

		$this->assertNotEmpty($order->status->charge_id);
		$this->verifyOrderProductsInDatabase($response->order->id, $products);
	}

	/**
	 * @param $order_id
	 * @param $products array of models
	 */
	protected function verifyOrderProductsInDatabase($order_id, $products) {
		foreach ($products as $product) {
			$this->seeInDatabase('order_product', [
				'order_id' => $order_id,
				'product_id' => $product->pivot->product_id,
				'product_vendor_price' => $product->pivot->vendor_price,
				'product_sale_price' => $product->pivot->sale_price,
				'vendor_id' => $product->pivot->vendor_id
			]);
		}
	}

	/**
	 * @param $products array of std class (all that is needed is ids)
	 * @param $address model
	 * @param $user model
	 * @param $token stripe token
	 * @param null $note
	 * @return mixed response content
	 */
	protected function createOrder($products, $address, $user, $token, $note = null) {
		$data = [
			'products' => $products,
			'address' => $address->toArray(),
			'user' => $user->toArray(),
			'stripe_token' => $token,
			'note' => $note
		];

		$this->post('/order', $data);

		return json_decode($this->response->getContent());
	}
}