<?php
use App\Http\Services\VendorHoursService;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/12/16
 * Time: 3:20 PM
 */
class VendorHoursServiceTest extends TestCase
{
	protected $vendorHoursService;

	public function setUp()
	{
		parent::setUp();
		$this->vendorHoursService = new VendorHoursService();
	}

	public function testGetOpenVendorsForDeliveryZone() {
		$deliveryZone = \App\Models\DeliveryZone::find(1);
		$res = $this->vendorHoursService->getOpenVendorsForDeliveryZone($deliveryZone);
		$h = 'h';
	}
}