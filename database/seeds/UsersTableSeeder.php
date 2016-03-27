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
		factory(App\Models\Entities\User::class, 20)->create()->each(function(\App\Models\Entities\User $u) {
			$u->addresses()->save(factory(App\Models\Entities\UserAddress::class)->make());
			$u->profile()->save(factory(\App\Models\Entities\UserProfile::class)->make());
		});

		// 10 more that are now gonna be vendors
		factory(App\Models\Entities\User::class, 10)->create()->each(function(\App\Models\Entities\User $u) {
			$u->vendor()->save(factory(App\Models\Entities\Vendor::class)->make());
			$vendor = $u->vendor;

			// lets add ten products to this vendor
			for ($i = 1; $i <= 20; $i++) {
				$product = \App\Models\Entities\Product::find($i);

				$price = rand(0, 100);

				$vendor->products()->attach($product->id, [
					'vendor_price' => $price,
					'sale_price' => $price + 1
				]);
			}
		});
    }
}
