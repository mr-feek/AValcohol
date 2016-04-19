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
	public function testContainsPoint() {
		$zone = DeliveryZone::create([
			'name' => 'NewZone',
			'points' => [new Point(0,0), new Point(0,4), new Point(4,4), new Point(4, 0)]
		]);

		$inZone = DeliveryZone::containsPoint(new Point(1, 1));
		$notInZone = DeliveryZone::containsPoint(new Point(4,5));

		$this->assertTrue($inZone);
		$this->assertFalse($notInZone);
	}
}