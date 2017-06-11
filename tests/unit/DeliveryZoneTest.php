<?php

use App\Models\DeliveryZone;
use App\Models\DeliveryZone\Point;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/19/16
 * Time: 2:50 PM
 */
class DeliveryZoneTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function setup() {
		parent::setup();
		DeliveryZone::create([
			'name' => 'Test1',
			'points' => [new Point(0,0), new Point(0,4), new Point(4,4), new Point(4, 0)]
		]);
	}

	public function testDoesContainPoint() {
		$zone = DeliveryZone::where(['name' => 'Test1'])->first();

		$inZone = $zone->doesContainPoint(new Point(1, 1));
		$notInZone = $zone->doesContainPoint(new Point(4,5));

		$this->assertTrue($inZone);
		$this->assertFalse($notInZone);
	}

	public function testGetZonesContainingPoint() {
		$inZone = DeliveryZone::getZonesContainingPoint(new Point(1, 1));
		$notInTestZone = DeliveryZone::getZonesContainingPoint(new Point(4,5));

		$this->assertFalse($inZone->isEmpty());

		// assert not of the zones returned are Test1
		foreach ($notInTestZone as $zone) {
			$this->assertNotEquals('Test1', $zone->name);
		}

		$zoneTwo = DeliveryZone::create([
			'name' => 'Zone 2',
			'points' => [new Point(0,0), new Point(0,2), new Point(2,2), new Point(2, 0)]
		]);

		$multipleZones = DeliveryZone::getZonesContainingPoint(new Point(1, 1));
		$this->assertTrue($multipleZones->count() >= 2);
	}

	public function testMyHouseIsInStateCollegeDeliveryZone() {
		$data = [
			'street' => '810 Walnut Street',
			'city' => 'State College',
			'state' => 'PA',
			'zipcode' => '16801',
			'location' => array(
				'longitude' => '-77.8538613',
				'latitude' => '40.788261'
			),
			'user' => [
				'id' => 1 // temp
			],
			'delivery_zone_id' => 1 // shouldn't affect what we are testing, just so service creation doesnt fail since it requires this
		];
		// create via service since it handles hydrating properties etc.
		$service = new \App\Http\Services\UserAddressService(
			new \App\Http\Repositories\UserAddressRepository(new \App\Models\UserAddress()),
			new \App\Http\Services\BlacklistedAddressService(new \App\Http\Repositories\BlacklistedAddressRepository(new \App\Models\BlacklistedAddress())),
			new \App\Http\Services\UserService(new \App\Http\Repositories\UserRepository(new \App\Models\User(), new \DrewM\MailChimp\MailChimp(env('MAILCHIMP_KEY'))))
		);
		
		$address = $service->create($data);

		$zones = DeliveryZone::getZonesContainingPoint($address->location);

		$stateCollegeZoneFound = false;
		// search for zone with name state college since i live in state college :)
		foreach($zones as $zone) {
			if($zone->name === 'State College') {
				$stateCollegeZoneFound = true;
				break;
			}
		}

		$this->assertTrue($stateCollegeZoneFound, '810 Walnut St was not found inside state college delivery zone');
	}
}