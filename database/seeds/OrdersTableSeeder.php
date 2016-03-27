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
			$products = [];

			// fetch 3 products to add to this order
			while (count($products) < 3) {
				// fetch a random product that a vendor has
				$random = rand(1, 100);
				$product = DB::table('vendor_product')->where('product_id', $random)->first();

				if (!$product) {
					continue;
				}

				$products[] = $product;
			}

			// attach these products to the order
			foreach($products as $p) {
				$o->products()->attach($p->product_id, [
					'product_vendor_price' => $p->vendor_price,
					'product_sale_price' => $p->sale_price,
					'vendor_id' => $p->vendor_id
				]);

				$o->amount += $p->sale_price;
			}

			// need to create default status record entry...
			$o->status()->save(factory(App\Models\Entities\OrderStatus::class)->make());

			$o->save();
		});
    }
}
