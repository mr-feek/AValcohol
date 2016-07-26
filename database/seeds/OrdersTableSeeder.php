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
		factory(App\Models\Order::class, 20)->create()->each(function(\App\Models\Order $o) {
			$faker = \Faker\Factory::create();
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

			// create charge in stripe
			\Stripe\Stripe::setApiKey(getenv('STRIPE_KEY'));
			$token = \Stripe\Token::create(array(
				"card" => array(
					"number" => "4242424242424242",
					"exp_month" => 12,
					"exp_year" => 2016,
					"cvc" => "314"
				)
			));

			$options = [
				'currency' => 'usd',
				'description' => 'test charge',
				'source' => $token,
				'receipt_email' => $o->user->email,
				'metadata' => array(
					'user_id' => $o->user->id,
					'order_id' => $o->id
				),
				'capture' => $faker->boolean()
			];

			$charge = $o->user->charge($o->full_charge_amount, $options);

			// need to create default status record entry...
			$o->status()->save(factory(App\Models\OrderStatus::class)->make([
				'charge_id' => $charge->id,
				'charge_captured' => $options['capture'],
				'charge_authorized' => true
			]));

			$o->save();
		});
    }
}
