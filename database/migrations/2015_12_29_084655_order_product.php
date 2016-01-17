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
					->on('orders')
					->onDelete('cascade');

			$table->foreign('product_id')
					->references('id')
					->on('products');
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
        Schema::drop('order_product');

		// add product ID to orders table
		Schema::table('orders', function(Blueprint $table) {
			$table->unsignedInteger('product_id');
			$table->foreign('product_id')->references('id')->on('products');
		});
    }
}
