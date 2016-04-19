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

	protected $address;

	public function setUp()
	{
		parent::setUp();
		$this->address =  \App\Models\UserAddress::orderByRaw('RAND()')->first();
	}

	public function testGetAllProductsForAddress() {
		$this->get('product?address_id=' . $this->address->id);

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

		$this->doesNotReturnCertainAttributes();
	}

	/*
	public function testGetAllFeaturedProductsForAddress() {
		$this->get('product/featured?address_id=' . $this->address->id);

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

		$products = $this->response->getContent();
		foreach($products as $product) {
			$this->assertTrue($product->featured, 1);
		}

		$this->doesNotReturnCertainAttributes();
	}
	*/

	private function doesNotReturnCertainAttributes() {
		$this->assertNotContains('vendor_price', $this->response->getContent());
	}

}