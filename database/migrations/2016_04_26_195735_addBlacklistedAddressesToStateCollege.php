<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\BlacklistedAddress;

class AddBlacklistedAddressesToStateCollege extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$deliverZone = \App\Models\DeliveryZone::where(['name' => 'State College'])->firstOrFail();
	    $deliverZoneId = $deliverZone->id;

	    // DOUBLE CHECK ALL OF THESE
	    $streetAddresses = [
		    '234 Locust Lane',
		    '425 Locust Lane',
		    '240 E Prospect Avenue',
		    '322 Fraternity Road',
		    '339 Fraternity Road',
		    '240 E Foster Avenue',
		    '320 S Fraser Street',
		    '328 E Fairmount Avenue',
		    '321 E Fairmount Avenue',
		    '360 N Burrowes Road',
		    '255 E Fairmount Avenue',
		    '220 N Burrowes Road',
		    '360 E Hamilton Avenue',
		    '424 E Fairmount Avenue',
		    '328 E Foster Avenue',
		    '508 Locust Lane',
		    '429 E Hamilton Avenue',
		    '101 N Patterson Street',
		    '229 Locust Lane',
		    '420 E Prospect Avenue',
		    '351 E Fairmount Avenue',
		    '319 N Burrowes Road',
		    '403 Locust Lane',
		    '234 E Beaver Avenue',
		    '338 E Fairmount Avenue',
		    '501 S Allen Street',
		    '417 E Prospect Avenue',
		    '321 Fraternity Road',
		    '200 E Beaver Avenue',
		    '329 E Prospect Avenue',
		    '400 E Prospect Avenue',
		    '340 N Burrowes Road',
		    '524 Locust Lane',
		    '303 Fraternity Road',
		    '500 S Allen Street',
		    '346 E Prospect Avenue',
		    '427 E Prospect Avenue',
		    '523 S Allen Street',
		    '305 E Prospect Avenue',
		    '226 E Beaver Avenue',
		    '225 E Foster Avenue'
	    ];

	    foreach($streetAddresses as $streetAddress) {
		    BlacklistedAddress::create([
			    'reason' => 1, // fraternity
			    'street' => $streetAddress,
			    'city' => 'State College',
			    'state' => 'PA',
			    'zipcode' => '16801',
			    'delivery_zone_id' => $deliverZoneId
		    ]);
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // gonna be lazy and just clear the table
		DB::statement("SET foreign_key_checks=0");
		BlacklistedAddress::truncate();
		DB::statement("SET foreign_key_checks=1");
    }
}
