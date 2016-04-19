<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductSalePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('products', function(Blueprint $table) {
			$table->decimal('sale_price', 11, 2)->after('price');
		});

		Schema::table('order_product', function(Blueprint $table) {
			$table->decimal('product_sale_price', 11, 2)->after('product_price');
			$table->renameColumn('product_price', 'product_vendor_price');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('products', function(Blueprint $table) {
			$table->dropColumn('sale_price');
		});

		Schema::table('order_product', function(Blueprint $table) {
			$table->dropColumn('product_sale_price');
			$table->renameColumn('product_vendor_price', 'product_price');
		});
    }
}
