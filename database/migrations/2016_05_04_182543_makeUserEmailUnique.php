<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserEmailUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    /*
	     * commenting out cause for mvp we don't have accounts and emails don't need to be unique
        Schema::table('users', function(Blueprint $table) {
			$table->unique('email');
		});
	    */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
