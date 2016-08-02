<?php
use App\Models\Vendor;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/6/16
 * Time: 6:27 PM
 */
class OrderStatusControllerTest extends TestCase
{
	protected $vendor;

	public function setUp()
	{
		parent::setUp();
		$this->vendor = Vendor::find(1);
		$this->token = $this->utils->generateTokenForUser($this->vendor->user);
		$this->authHeader = ['Authorization' => 'Bearer ' . $this->token];
	}

	public function testVendorAcceptOrder() {
		$order = $this->createVendorPendingOrder();
		$data = [
			'vendor_status' => 'accepted'
		];

		$this->expectEmailWithViewNamed('order-accepted');

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'pending',
			'vendor_status' => 'accepted'
		]);

		// verify charge was captured
		\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET'));
		$charge = \Stripe\Charge::retrieve($order->status->charge_id);
		$this->assertTrue($charge->captured, 'the charge does not seem to have been captured on stripe.');
		$this->assertEquals($order->calculateChargeAmountForProcessor(), $charge->amount);
	}

	public function testVendorRejectOrder() {
		$order = $this->createVendorPendingOrder();
		$data = [
			'vendor_status' => 'rejected'
		];

		$this->expectEmailWithViewNamed('order-not-accepted');

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'pending',
			'vendor_status' => 'rejected'
		]);

		// verify charge deleted
		\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET'));
		$charge = \Stripe\Charge::retrieve($order->status->charge_id);
		$this->assertFalse($charge->captured, 'charge seems to be captured when it should not be.');
		$this->assertTrue($charge->refunded, 'charge does not seem to have been refunded. Not a big deal, as it should cancel itself in 7 days anyway');
	}

	public function testDriverPickupOrder() {
		$order = $this->fetchDeliveryPendingOrder();
		$data = [
			'delivery_status' => 'out-for-delivery'
		];

		$this->expectEmailWithViewNamed('out-for-delivery');

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'out-for-delivery',
			'vendor_status' => 'accepted'
		]);
	}

	public function testDriverDeliverOrder() {
		// this is handled in delivery details
		//$this->fail();
	}

	/**
	 * returns an order with vendor status === pending
	 * @return mixed
	 */
	private function createVendorPendingOrder() {
		$user = null;
		factory(\App\Models\User::class)->create()->each(function(\App\Models\User $u) use (&$user) {
			$u->address()->save(factory(\App\Models\UserAddress::class)->make());
			$u->profile()->save(factory(\App\Models\UserProfile::class)->make());

			$user = $u;
		});

		return $this->createOrderSeed([
			'user_id' => $user->id,
			'user_address_id' => $user->address->id
		], [
			'vendor_status' => 'pending',
			'delivery_status' => 'pending',
			'charge_captured' => false
		]);
	}

	private function fetchDeliveryPendingOrder() {
		$order = \App\Models\Order::whereHas('status', function(\Illuminate\Database\Eloquent\Builder $query) {
			$query->where('vendor_status', 'accepted');
			$query->where('delivery_status', 'pending');
		})->firstOrFail();
		
		return $order;
	}

	private function expectEmailWithViewNamed(string $name)
	{
		\Illuminate\Support\Facades\Mail::shouldReceive('queue')->once()->andReturnUsing(function ($view, $viewParams) use($name) {
			$this->assertEquals("emails.{$name}", $view['text']);
		});
	}

	// fuck it literally just copying the order table seeder and putting it here so i can call it easily
	private function createOrderSeed($attributes, $orderStatusAttributes) {
		$model = null;
		factory(App\Models\Order::class)->create($attributes)->each(function(\App\Models\Order $o) use ($orderStatusAttributes, &$model) {
			$faker = \Faker\Factory::create();
			$products = [];

			// fetch 3 products to add to this order
			while (count($products) < 3) {
				// fetch a random product that a vendor has
				$random = rand(1, 10);
				$product = \App\Models\Vendor::find($o->vendor_id)->products()->where('product_id', $random)->first();

				if (!$product) {
					continue;
				}

				$products[] = $product;
			}

			// attach these products to the order
			$o->addMultipleProducts($products)->calculateDeliveryFee();

			// create charge in stripe
			\Stripe\Stripe::setApiKey(getenv('STRIPE_KEY'));
			$token = \Stripe\Token::create(array(
				"card" => array(
					"number" => "4242424242424242",
					"exp_month" => 12,
					"exp_year" => 2016,
					"cvc" => "314"
				)
			));

			$toCapture = array_key_exists('charge_captured', $orderStatusAttributes) ? $orderStatusAttributes['charge_captured'] : $faker->boolean();

			$options = [
				'currency' => 'usd',
				'description' => 'test charge',
				'source' => $token,
				'receipt_email' => $o->user->email,
				'metadata' => array(
					'user_id' => $o->user->id,
					'order_id' => $o->id
				),
				'capture' => $toCapture
			];

			$charge = $o->user->charge($o->calculateChargeAmountForProcessor(), $options);

			// need to create default status record entry...
			$defaultAttributes = [
				'charge_id' => $charge->id,
				'charge_captured' => $toCapture,
				'charge_authorized' => true
			];

			$attributesToMake = array_merge($defaultAttributes, $orderStatusAttributes);

			$o->status()->save(factory(App\Models\OrderStatus::class)->make($attributesToMake));

			$o->save();

			$model = $o;
		});

		return $model;
	}
}