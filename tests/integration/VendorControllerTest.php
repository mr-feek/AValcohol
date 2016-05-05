<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/3/16
 * Time: 3:48 PM
 */
class VendorControllerTest extends TestCase
{
	/*
	public function testGetAllOrdersForVendor() {
		$this->get('vendor/orders');
		$this->verifyJsonStructure();

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertTrue($order->status->charge_authorized);
		}
	}
	*/

	public function testGetPendingOrdersForVendor() {
		$this->get('vendor/orders/pending');
		$this->verifyJsonStructure();

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertEquals($order->status->vendor_status, 'pending');
			$this->assertTrue((boolean) $order->status->charge_authorized);
			$this->assertFalse((boolean) $order->status->charge_captured);
		}
	}

	protected function verifyJsonStructure() {
		$this->seeJsonStructure([
			'orders' => [
				'*' => [
					'id',
					'amount',
					'note',
					'user' => [
						'profile' => [
							'first_name',
							'last_name',
							'date_of_birth'
						]
					],
					'user_address_id',
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
								'product_vendor_price'
							],
						],
					],
				],
			]
		]);
	}

	public function testCreateVendor() {
		$data = [
			'email' => 'asdf@asd.com',
			'password' => 'password12',
			'name' => 'first last',
			'address' => '123 candy cane lane',
			'phone_number' => '1231231234',
			'delivery_zone_id' => '1'
		];

		$this->post('/vendor', $data);

		$this->seeJsonStructure([
			'vendor' => [
				'name',
				'address',
				'phone_number',
				'delivery_zone_id'
			]
		]);

		$response = json_decode($this->response->getContent());

		$this->seeInDatabase('vendors', [
			'id' => $response->vendor->id,
			'name' => $data['name'],
			'address' => $data['address'],
			'phone_number' => $data['phone_number'],
			'delivery_zone_id' => $data['delivery_zone_id'],
			'user_id' => $response->vendor->user_id
		]);

		$this->seeInDatabase('users', [
			'id' => $response->vendor->user_id,
			'email' => $data['email'],
		]);

		// ensure password got hashed
		$this->notSeeInDatabase('users', [
			'password' => $data['password']
		]);
	}
}