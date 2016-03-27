<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('phone_number');
		});

		Schema::create('user_profiles', function(Blueprint $table) {
			$table->string('first_name');
			$table->string('last_name');
			$table->string('phone_number');
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
		});

		Schema::create('vendors', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('address');
			$table->unsignedBigInteger('phone_number');
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
		});

		Schema::table('products', function(Blueprint $table) {
			$table->dropColumn('price');
			$table->dropColumn('sale_price');
		});

		Schema::create('vendor_product', function(Blueprint $table) {
			$table->unsignedInteger('vendor_id');
			$table->unsignedInteger('product_id');
			$table->decimal('vendor_price', 11, 2); // 2 decimal places
			$table->decimal('sale_price', 11, 2); // 2 decimal places
			$table->softDeletes();
			$table->timestamps();

			$table->foreign('vendor_id')
				->references('id')->on('vendors')
				->onDelete('cascade');

			$table->foreign('product_id')
				->references('id')->on('products')
				->onDelete('cascade');
		});

		Schema::create('vendor_settings', function(Blueprint $table) {
			$table->unsignedInteger('vendor_id');
			$table->boolean('auto_accept_orders');
			$table->timestamps();

			$table->foreign('vendor_id')
				->references('id')->on('vendors')
				->onDelete('cascade');
		});

		Schema::table('orders', function(Blueprint $table) {
			// might have an issue here because modifying table with enum is not supported... will have to drop hole table and recreate
			$table->dropColumn('status');
		});

		Schema::table('order_product', function(Blueprint $table) {
			$table->unsignedInteger('vendor_id');

			$table->foreign('vendor_id')
				->references('id')->on('vendors')
				->onDelete('cascade');
		});

		Schema::create('order_denials', function(Blueprint $table) {
			$table->unsignedInteger('order_id');
			$table->string('reason');
			$table->timestamps();
		});

		Schema::create('order_statuses', function(Blueprint $table) {
			$table->unsignedInteger('order_id');
			$table->string('vendor_status');
			$table->string('delivery_status');
			$table->timestamps();

			$table->foreign('order_id')
				->references('id')->on('orders')
				->onDelete('cascade');
		});

		Schema::create('order_delivery_details', function(Blueprint $table) {
			$table->unsignedInteger('order_id');
			$table->string('picture_url');
			$table->string('signature'); // probably gonna mess around with this...
			$table->timestamps();

			$table->foreign('order_id')
				->references('id')->on('users')
				->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //ugggggg
		Schema::drop('order_delivery_details');
		Schema::drop('order_statuses');
		Schema::drop('order_denials');
		Schema::drop('vendor_settings');
		Schema::drop('vendor_products');
		Schema::drop('vendors');
		Schema::drop('user_profiles');

		Schema::table('users', function(Blueprint $table) {
			$table->string('first_name');
			$table->string('last_name');
			$table->unsignedBigInteger('phone_number');
		});

		Schema::table('products', function(Blueprint $table) {
			$table->decimal('price', 11, 2); // 2 decimal places
			$table->decimal('sale_price', 11, 2); // 2 decimal places
		});

		Schema::table('orders', function(Blueprint $table) {
			$table->string('status');
		});

		Schema::table('order_product', function(Blueprint $table) {
			$table->dropColumn('vendor_id');

			$table->dropForeign('vendor_id');
		});
    }
}
