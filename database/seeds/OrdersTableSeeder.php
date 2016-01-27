<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(App\Models\Entities\Order::class, 20)->create()->each(function(\App\Models\Entities\Order $o) {
			// attach two products to the order and update the order amount
			$random = rand(1, 10);
			$product1 =\App\Models\Entities\Product::find($random);
			$o->products()->attach($product1->id, [
				'product_vendor_price' => $product1->price,
				'product_sale_price' => $product1->sale_price
			]);

			$random = rand(1, 10);
			$product2 = \App\Models\Entities\Product::find($random);
			$o->products()->attach($product2->id, [
				'product_vendor_price' => $product2->price,
				'product_sale_price' => $product2->sale_price
			]);

			$o->amount = $product1->sale_price + $product2->sale_price;
			$o->save();
		});
    }
}
