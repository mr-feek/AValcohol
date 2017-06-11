<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminLoginAsVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_login_as_vendor', function(Blueprint $table) {
			$table->increments('id');
	        $table->unsignedInteger('user_id');
	        $table->unsignedInteger('vendor_id');
	        $table->timestamps();

	        $table->foreign('user_id')->references('id')->on('users');
	        $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::drop('admin_login_as_vendor');
    }
}
