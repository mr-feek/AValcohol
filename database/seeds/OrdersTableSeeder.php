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
	    $users = \App\Models\User::orderByRaw('RAND()')->get();
	    // don't create an order for a user that is a vendor
	    $user = null;

	    foreach($users as $u) {
		    if ($u->isVendor() === false) {
			    $user = $u;
			    break;
		    }
	    }

	    if (!$user) {
		    dd('all users in db are apparently vendors');
	    }

		factory(App\Models\Order::class, 10)->create([
			'user_id' => $user->id,
			'user_address_id' => $user->address->id,
			'vendor_id' => 1
		])->each(function(\App\Models\Order $o) {
			$faker = \Faker\Factory::create();
			$products = [];

			// fetch 3 products to add to this order
			while (count($products) < 3) {
				// fetch a random product that a vendor has
				$random = rand(1, 10);
				$product = \App\Models\Vendor::find(1)->products()->where('product_id', $random)->first();
				
				if (!$product) {
					continue;
				}

				$products[] = $product;
			}

			// attach these products to the order
			$o->addMultipleProducts($products)->calculateDeliveryFee();
			
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

			$charge = $o->user->charge($o->calculateChargeAmountForProcessor(), $options);

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
