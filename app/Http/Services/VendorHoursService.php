<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/11/16
 * Time: 2:37 PM
 */

namespace App\Http\Services;

/*
|--------------------------------------------------------------------------
| Vendor Hours Service
|--------------------------------------------------------------------------
|
| this service is responsible for compiling data from the vendor hours model
| as well as the override vendor hours model and determining the currently
| open vendors etc. May need to use a repository too in the future.
|
*/

use App\Models\DeliveryZone;
use App\Models\Vendor;

class VendorHoursService extends BaseService
{
	public function construct() {

	}
	
	public function getOpenVendorsForDeliveryZone(DeliveryZone $deliveryZone) {
		$deliveryZoneId = $deliveryZone->id;

		$query = <<<SQL

			SELECT vendor_hours.vendor_id
			FROM vendor_hours
			LEFT JOIN vendor_hours_overrides
			ON vendor_hours.vendor_id = vendor_hours_overrides.vendor_id
				AND vendor_hours.vendor_id = vendor.id 
					WHERE vendor.delivery_zone_id = {$deliveryZoneId}
					AND vendor_hours.day_of_week = vendor_hours_overrides.day_of_week
					AND NOW() BETWEEN vendor_hours_overrides.override_start_date AND vendor_hours_overrides.override_end_date
						WHERE vendor_hours.day_of_week = WEEKDAY(NOW())
						
SQL;

		//AND vendor_hours_overrides.vendor_id IS NOT NULL AND vendor_hours_overrides.closed <> 1 AND TIME(NOW()) BETWEEN vendor_hours_overrides.alternate_open_time AND vendor_hours_overrides.alternate_close_time;

		//$res = DB::statement($query);

		$vendors = Vendor::isOpen()->whereDeliveryZoneId($deliveryZoneId)->get();
		
		return $vendors;
	}

	/**
	 * returns whether or not there are any open vendors in the provided
	 * delivery zone.
	 *
	 * @param DeliveryZone $deliveryZone
	 */
	public function areOpenVendorsInDeliveryZone(DeliveryZone $deliveryZone) {
		
	}
}