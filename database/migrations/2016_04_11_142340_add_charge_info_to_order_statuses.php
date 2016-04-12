<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChargeInfoToOrderStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_statuses', function(Blueprint $table) {
			$table->string('charge_id')->after('order_id');
			$table->boolean('charge_authorized')->after('charge_id')->default(0);
			$table->boolean('charge_captured')->after('charge_authorized')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
	{
		Schema::table('order_statuses', function(Blueprint $table) {
			$table->dropColumn('charge_id');
			$table->dropColumn('charge_authorized');
			$table->dropColumn('charge_captured');
		});
    }
}
