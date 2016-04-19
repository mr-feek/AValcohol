<?php

use App\Models\DeliveryZone;
use App\Models\DeliveryZone\Point;
use \Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/19/16
 * Time: 2:50 PM
 */
class DeliveryZoneTest extends TestCase
{
	/*
	public function testDoesContainPoint() {
		$zone = DeliveryZone::create([
			'name' => 'NewZone',
			'points' => [new Point(0,0), new Point(0,4), new Point(4,4), new Point(4, 0)]
		]);

		$inZone = $zone->doesContainPoint(new Point(1, 1));
		$notInZone = $zone->doesContainPoint(new Point(4,5));

		$this->assertTrue($inZone);
		$this->assertFalse($notInZone);
	}
	*/

	public function testGetZonesContainingPoint() {
		$zone = DeliveryZone::create([
			'name' => 'NewZone',
			'points' => [new Point(0,0), new Point(0,4), new Point(4,4), new Point(4, 0)]
		]);

		$zone = DeliveryZone::getZonesContainingPoint(new Point(1, 1));
		$noZone = DeliveryZone::getZonesContainingPoint(new Point(4,5));

		$this->assertFalse($zone->isEmpty());
		$this->assertTrue($noZone->isEmpty());

		$zoneTwo = DeliveryZone::create([
			'name' => 'Zone 2',
			'points' => [new Point(0,0), new Point(0,2), new Point(2,2), new Point(2, 0)]
		]);

		$multipleZones = DeliveryZone::getZonesContainingPoint(new Point(1, 1));
		$this->assertTrue($multipleZones->count() >= 2);
	}
}