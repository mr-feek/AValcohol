<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('products', function ($table) {
			// how many beers in this pack
			$table->enum('contains', ['6', '12', '24', '30'])->after('price');
			// can size
			$table->enum('ounces', ['12', '16', '24', '36'])->after('contains');
			// can or bottle
			$table->enum('container', ['can', 'bottle'])->after('ounces');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function($table) {
			$table->dropColumn(['contains', 'ounces', 'container']);
		});
    }
}
