<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApartmentNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function(Blueprint $table) {
            $table->string('apartment_number', 10)->after('zipcode'); // it shouldnt ever be more than this? maybe var char instead idk. keeping it string in case A, B, C etc.
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
		    $table->dropColumn('apartment_number'); // it shouldnt ever be more than this? maybe var char instead idk. keeping it string in case A, B, C etc.
	    });
    }
}
