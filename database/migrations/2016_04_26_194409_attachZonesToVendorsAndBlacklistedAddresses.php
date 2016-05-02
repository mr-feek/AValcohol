<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttachZonesToVendorsAndBlacklistedAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function(Blueprint $table) {
			$table->unsignedInteger('delivery_zone_id')->after('phone_number');

			$table->foreign('delivery_zone_id')
				->references('id')->on('delivery_zones')
				->onDelete('no action');
		});

		Schema::table('blacklisted_addresses', function(Blueprint $table) {
			$table->unsignedInteger('delivery_zone_id')->after('zipcode');

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
		Schema::table('vendors', function(Blueprint $table) {
			$table->dropColumn('delivery_zone_id');
			$table->dropForeign('delivery_zone_id');
		});

		Schema::table('blacklisted_addresses', function(Blueprint $table) {
			$table->dropColumn('delivery_zone_id');
			$table->dropForeign('delivery_zone_id');
		});
    }
}
