<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(App\Models\User::class, 20)->create()->each(function(\App\Models\User $u) {
			$u->addresses()->save(factory(App\Models\UserAddress::class)->make());
			$u->profile()->save(factory(\App\Models\UserProfile::class)->make());
		});

		// 10 more that are now gonna be vendors
		factory(App\Models\User::class, 10)->create()->each(function(\App\Models\User $u) {
			$u->vendor()->save(factory(App\Models\Vendor::class)->make());
			$vendor = $u->vendor;

			// lets add ten products to this vendor
			for ($i = 1; $i <= 20; $i++) {
				$product = \App\Models\Product::find($i);

				$price = rand(0, 100);

				$vendor->products()->attach($product->id, [
					'vendor_price' => $price,
					'sale_price' => $price + 1
				]);
			}

			// create the settings entry
			$vendor->settings()->save(factory(App\Models\VendorSetting::class)->make());
		});
    }
}
