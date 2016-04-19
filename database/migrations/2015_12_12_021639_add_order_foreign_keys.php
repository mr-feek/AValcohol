<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderForeignKeys extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products') ->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users') ->onDelete('cascade');
			$table->foreign('user_address_id')->references('id')->on('user_addresses') ->onDelete('cascade');
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
			//$table->dropForeign('orders_product_id_foreign'); // dropped in later migration
			$table->dropForeign('orders_user_id_foreign');
			$table->dropForeign('orders_user_address_id_foreign');
		});
	}
}
