<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlacklistAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklisted_addresses', function(Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('reason'); // reason as to why it was blacklisted
			$table->string('street');
			$table->string('city');
			$table->string('state'); // could be an enum of all 2 letter state abbreviations
			$table->integer('zipcode');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blacklisted_addresses');
    }
}
