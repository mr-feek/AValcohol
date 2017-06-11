<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryZoneIdToAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function(Blueprint $table) {
			$table->unsignedInteger('delivery_zone_id')->after('location');

			$table->foreign('delivery_zone_id')
				->references('id')->on('delivery_zones')
				->onDelete('no action');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('user_addresses', function(Blueprint $table) {
			$table->dropForeign('user_addresses_delivery_zone_id_foreign');
			$table->dropColumn('delivery_zone_id');
		});
    }
}
