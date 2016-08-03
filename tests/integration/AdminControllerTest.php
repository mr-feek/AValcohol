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
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testGetOrdersReadyToBePickedUp() {
		$this->refreshApplication();
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