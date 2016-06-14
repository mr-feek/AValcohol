<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:08 PM
 */
class AdminControllerTest extends TestCase
{
	protected $user;

	public function setUp()
	{
		parent::setUp();

		$this->user = User::whereHas('roles', function($query) {
			$query->where('role_id', 1);
		})->first();

		$this->token = $this->utils->generateTokenForUser($this->user);
		$this->authHeader = ['Authorization' => 'Bearer ' . $this->token];
	}

	public function testGetOrdersReadyToBePickedUp() {
		$this->refreshApplication();
		$this->get('/admin/orders?ready', $this->authHeader);

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertEquals($order->status->vendor_status, 'accepted');
			$this->assertEquals($order->status->delivery_status, 'pending');
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
			$this->assertEquals($order->status->vendor_status, 'accepted');
			$this->assertEquals($order->status->delivery_status, 'out-for-delivery');
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