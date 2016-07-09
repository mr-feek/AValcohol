<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_hours', function(Blueprint $table) {
			$table->increments('id');
	        $table->unsignedInteger('vendor_id');
	        $table->integer('day_of_week');
	        $table->time('open_time');
	        $table->time('close_time');
	        $table->timestamps();

	        $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

	    Schema::create('vendor_hours_overrides', function(Blueprint $table) {
		    $table->increments('id');
		    $table->unsignedInteger('vendor_id');
		    $table->integer('day_of_week');
			$table->date('override_start_date');
		    $table->date('override_end_date');
		    $table->time('alternate_open_time');
		    $table->time('alternate_close_time');
		    $table->boolean('closed'); // if not open at all on this day
		    $table->timestamps();

		    $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::drop('vendor_hours');
	    Schema::drop('vendor_hours_overrides');
    }
}
