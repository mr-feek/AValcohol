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
		factory(App\Models\Order::class, 40)->create()->each(function(\App\Models\Order $o) {
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

				$o->full_charge_amount += $p->sale_price;
				$o->vendor_charge_amount += $p->vendor_price;
			}

			$o->tax_charge_amount = $o->vendor_charge_amount * 0.06;

			// need to create default status record entry...
			$o->status()->save(factory(App\Models\OrderStatus::class)->make());

			$o->save();
		});
    }
}
