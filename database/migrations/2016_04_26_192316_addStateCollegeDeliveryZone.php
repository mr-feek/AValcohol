<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\DeliveryZone\Point;
use App\Models\DeliveryZone;

class AddStateCollegeDeliveryZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DeliveryZone::create([
			'name' => 'State College',
			'points' => [
				new Point(40.779685, -77.880106),
				new Point(40.771691, -77.87075),
				new Point(40.772211, -77.866373),
				new Point(40.769481, -77.860794),
				new Point(40.776826, -77.848864),
				new Point(40.777021, -77.843971),
				new Point(40.782675, -77.835646),
				new Point(40.788004, -77.838478),
				new Point(40.794112, -77.840538),
				new Point(40.802234, -77.85058)
			]
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DeliveryZone::where(['name' => 'State College'])->delete();
    }
}
