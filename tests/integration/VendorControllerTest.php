<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/3/16
 * Time: 3:48 PM
 */
class VendorControllerTest extends TestCase
{
	public function testGetAllOrdersForVendor() {
		$this->get('vendor/orders');
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
								'product_vendor_price'
							],
						],
					],
				],
			]
		]);
	}
}