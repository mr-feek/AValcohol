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
		$this->ensureAVendorIsOpenNow();
		$this->get('product?delivery_zone_id=' . $this->address->delivery_zone_id);

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

	/**
	 * grabs all vendors for the given address and opens them up for 2 hours today (now)
	 */
	private function ensureAVendorIsOpenNow() {
		$vendorService = app()->make(\App\Http\Services\VendorService::class);
		$vendors = $vendorService->getVendorsForAddress($this->address);

		foreach($vendors as $vendor) {
			// add an entry for today with the store open an hour ago and closing in an hour
			factory(\App\Models\VendorStoreHours::class)->create([
				'vendor_id' => $vendor->id,
				'day_of_week' => \Carbon\Carbon::today()->dayOfWeek,
				'open_time' => \Carbon\Carbon::now()->subHours(1),
				'close_time' => \Carbon\Carbon::now()->addHours(1)
			]);
		}
	}

}