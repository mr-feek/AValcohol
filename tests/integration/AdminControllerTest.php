<?php

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserProfile;
use App\Models\Vendor;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:08 PM
 */
class AdminControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testGetOrdersReadyToBePickedUp() {
		$this->refreshApplication();

		$users = factory(User::class, 2)->create()->each(function(User $user) {
			$user->profile()->save(factory(UserProfile::class)->make());
			$user->address()->save(factory(UserAddress::class)->make());
		});

		$user = $users->first();

		$orders = factory(Order::class, 2)->create([
			'user_id' => $user->id,
			'user_address_id' => $user->address->id,
			'vendor_id' => 1
		])->each(function(Order $order) {
			$faker = Factory::create();
			$products = [];

			// fetch 3 products to add to this order
			while (count($products) < 3) {
				// fetch a random product that a vendor has
				$random = rand(1, 10);
				$product = Vendor::find(1)->products()->where('product_id', $random)->first();

				if (!$product) {
					continue;
				}

				$products[] = $product;
			}

			// attach these products to the order
			$order->addMultipleProducts($products)->calculateDeliveryFee();

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

			$options = [
				'currency' => 'usd',
				'description' => 'test charge',
				'source' => $token,
				'receipt_email' => $order->user->email,
				'metadata' => array(
					'user_id' => $order->user->id,
					'order_id' => $order->id
				),
				'capture' => $faker->boolean()
			];

			$charge = $order->user->charge($order->calculateChargeAmountForProcessor(), $options);

			$order->status()->save(factory(OrderStatus::class)->make([
				'vendor_status' => 'accepted',
				'delivery_status' => 'pending',
				'charge_authorized' => true,
				'charge_captured' => true
			]));
		});

		$this->get('/admin/orders?ready', $this->authHeader);

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertEquals('accepted', $order->status->vendor_status);
			$this->assertEquals('pending', $order->status->delivery_status);
			$this->assertTrue((boolean) $order->status->charge_authorized);
			$this->assertTrue((boolean) $order->status->charge_captured);
		}

		$this->verifyJsonStructure();
	}

	public function testGetOrdersOutForDelivery() {
		$this->refreshApplication();
		$this->get('/admin/orders?out', $this->authHeader);

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertEquals('accepted', $order->status->vendor_status);
			$this->assertEquals('out-for-delivery', $order->status->delivery_status);
			$this->assertTrue((boolean) $order->status->charge_authorized);
			$this->assertTrue((boolean) $order->status->charge_captured);
		}

		$this->verifyJsonStructure();
	}

	protected function verifyJsonStructure() {
		$this->seeJsonStructure([
			'orders' => [
				'*' => [
					'id',
					'full_charge_amount',
					'vendor_charge_amount',
					'note',
					'user' => [
						'profile' => [
							'first_name',
							'last_name',
							'date_of_birth'
						]
					],
					'created_at',
					'products' => [
						'*' => [
							'id',
							'upc',
							'name',
							'contains',
							'ounces',
							'container',
							'image_url',
							'pivot' => [
								'product_sale_price'
							],
						],
					],
					'address' => [
						'id',
						'city',
						'state',
						'street',
						'zipcode'
					]
				]
			]
		]);
	}
}
