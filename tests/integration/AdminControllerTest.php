<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:08 PM
 */
class AdminControllerTest extends TestCase
{
	public function testGetOrdersReadyToBePickedUp() {
		$this->get('/admin/orders/ready');

		$orders = json_decode($this->response->getContent())->orders;
		foreach($orders as $order) {
			$this->assertEquals($order->status->vendor_status, 'accepted');
			$this->assertEquals($order->status->delivery_status, 'pending');
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
								'product_sale_price'
							],
						],
					],
				]
			]
		]);
	}
}