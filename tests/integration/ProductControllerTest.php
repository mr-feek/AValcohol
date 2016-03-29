<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/27/16
 * Time: 9:48 PM
 */
class ProductControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function testGetAllProductsForAddress() {
		// select random address
		$address = \App\Models\Entities\UserAddress::orderByRaw('RAND()')->first();

		$this->get('product/all?address_id=' . $address->id);

		$this->seeJsonStructure([
			'products' => [
				'*' => [
					'id', 'upc', 'name', 'contains', 'ounces', 'container', 'featured', 'image_url',
					'pivot' => [
						'vendor_id',
						'product_id',
						'sale_price'
					]
				]
			]
		]);

		$this->assertNotContains('vendor_price', $this->response->getContent());
	}

}