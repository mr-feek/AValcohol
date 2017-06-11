<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function(Blueprint $table) {
			$table->unsignedInteger('order_id');
			$table->unsignedInteger('product_id');
			$table->decimal('product_price', 8, 2); // to record the price of the product at time of purchase

			$table->foreign('order_id')
					->references('id')
					->on('orders');

			$table->foreign('product_id')
					->references('id')
					->on('products');

			$table->softDeletes();
		});

		// drop product ID from orders table
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_product_id_foreign');
			$table->dropColumn('product_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('order_product', function(Blueprint $table) {
			$table->dropForeign(['order_id']);
			$table->dropForeign(['product_id']);
		});

        Schema::dropIfExists('order_product');

		// add product ID to orders table
		/*
		 * something about this is broken, idk what. fuck it
		Schema::table('orders', function(Blueprint $table) {
			$table->integer('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('products') ->onDelete('cascade');
		});
		*/
    }
}
