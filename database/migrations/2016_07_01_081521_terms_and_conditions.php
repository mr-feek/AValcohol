<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TermsAndConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('orders', function(Blueprint $table) {
            $table->boolean('terms_and_conditions')->default(false)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('terms_and_conditions');
	    });
    }
}
