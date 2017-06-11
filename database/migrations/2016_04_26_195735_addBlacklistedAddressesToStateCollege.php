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
	    $addresses = [
		    '234 Locust Ln',
		    '425 Locust Ln',
		    '240 E Prospect Ave',
		    '322 Fraternity Row',
		    '339 Fraternity Row',
		    '240 E Foster Ave',
		    '320 S Fraser St',
		    '328 E Fairmount Ave',
		    '321 E Fairmount Ave',
		    '360 N Burrowes St',
		    '255 E Fairmount Ave',
		    '220 N Burrowes St',
		    '360 E Hamilton Ave',
		    '424 E Fairmount Ave',
		    '328 E Foster Ave',
		    '508 Locust Ln',
		    '429 E Hamilton Ave',
		    '101 N Patterson St',
		    '229 Locust Ln',
		    '420 E Prospect Ave',
		    '351 E Fairmount Ave',
		    '319 N Burrowes St',
		    '403 Locust Ln',
		    '234 E Beaver Ave',
		    '338 E Fairmount Ave',
		    '501 S Allen St',
		    '417 E Prospect Ave',
		    '321 Fraternity Row',
		    '200 E Beaver Ave',
		    '329 E Prospect Ave',
		    '400 E Prospect Ave',
		    '340 N Burrowes St',
		    '524 Locust Ln',
		    '303 Fraternity Row',
		    '500 S Allen St',
		    '346 E Prospect Ave',
		    '427 E Prospect Ave',
		    '523 S Allen St',
		    '305 E Prospect Ave',
		    '226 E Beaver Ave',
		    '225 E Foster Ave'
	    ];

	    foreach($addresses as $address) {
		    BlacklistedAddress::create([
			    'reason' => 1, // fraternity
			    'street' => $address,
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
