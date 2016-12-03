<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SiteStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_status', function(Blueprint $table) {
	        $table->unsignedInteger('id'); // don't auto increment because we really only want a single entry in this table
	        $table->boolean('admin_force_offline')->default(0);
            $table->timestamps();
        });

	    // populate field
	    \App\Models\SiteStatus::create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_status');
    }
}
