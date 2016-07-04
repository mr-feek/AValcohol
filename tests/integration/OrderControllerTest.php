<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/29/15
 * Time: 4:39 AM
 */

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Vendor;

class OrderControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function testCreateOrderWithExistingUser() {
		$this->withoutMiddleware();

		$products = $this->getProductsToBuy();
		$address = $this->getAddress();
		$user = User::find(1);
		$token = $this->createFakeToken();

		$response = $this->createOrder($products, $address, $user, $token);

		$this->verifyFullOrderInDatabase($response, $products);
	}

	public function testCreateOrderWithExistingUserWithQuantities() {
		$this->withoutMiddleware();

		$products = $this->getProductsToBuy(3); // 3 quantity for each
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
			'message' => 'No query results for model [App\\Models\\User].'
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
			'message' => 'No query results for model [App\\Models\\UserAddress].'
		]);
	}

	public function testCreateOrderFailsWithoutAcceptingTermsAndConditions() {
		$this->withoutMiddleware();
		$products = $this->getProductsToBuy();

		$user = User::find(1);
		$address = $this->getAddress();
		$token = $this->createFakeToken();

		$this->createOrder($products, $address, $user, $token, 'note here', false);

		$this->seeJsonContains([
			'terms_and_conditions' => ['The terms and conditions must be accepted.']
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
		$products[1]->pivot->vendor_id = 0;

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "No query results for model [App\\Models\\Vendor]."]);
	}

	public function testCreateOrderWithNote() {
		$this->withoutMiddleware();

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
		$address = factory(\App\Models\UserAddress::class)->create(['zipcode' => $zip, 'user_id' => 1]);

		$this->createOrder($products, $address, $user, $token);
		$this->verifyOrderNotCreated();
		$this->seeJson(['message' => "We're sorry, but at this time we can only deliver to the 16801 area"]);
	}
	*/

	protected function verifyOrderNotCreated() {
		$this->seeJson(['success' => false]);
	}

	protected function getProductsToBuy($quantity = 1) {
		$vendor = Vendor::find(1);
		$products = $vendor->products()->take(3)->get();
		foreach ($products as $p) {
			$p->quantity = $quantity;
		}
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
		if (env('OFFLINE_MODE') === true) {
			return 'fake-token-due-to-offline-mode-enabled';
		}

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
		$vendorAmount = 0;

		foreach ($products as $product) {
			for ($i = 0; $i < $product->quantity; $i++) {
				$amount += $product->pivot->sale_price;
				$vendorAmount += $product->pivot->vendor_price;
			}
		}

		$taxChargeAmount = 0.06 * $vendorAmount;

		//dd($amount, $vendorAmount);

		$this->seeInDatabase('orders', [
			'id' => $response->order->id,
			'full_charge_amount' => $amount,
			'vendor_charge_amount' => $vendorAmount,
			'tax_charge_amount' => $taxChargeAmount,
			'user_id' => $response->order->user_id,
			'user_address_id' => $response->order->user_address_id,
			'terms_accepted' => true
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
		$order = \App\Models\Order::find($response->order->id);

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
	 * @param bool $termsAccepted
	 * @return mixed response content
	 */
	protected function createOrder($products, $address, $user, $token, $note = null, $termsAccepted = true) {
		$data = [
			'products' => $products->toArray(),
			'address' => $address->toArray(),
			'user' => $user->toArray(),
			'stripe_token' => $token,
			'note' => $note,
			'terms_and_conditions' => $termsAccepted
		];

		$this->post('/order', $data);

		return json_decode($this->response->getContent());
	}
}