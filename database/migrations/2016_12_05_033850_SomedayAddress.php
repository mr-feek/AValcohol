<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SomedayAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('someday_addresses', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        DB::statement(<<<SQL
			ALTER TABLE someday_addresses 
			ADD location POINT NOT NULL 
			AFTER id
SQL
);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('someday_addresses');
    }
}
