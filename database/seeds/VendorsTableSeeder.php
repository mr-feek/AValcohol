<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/5/16
 * Time: 11:51 AM
 */
class VendorsTableSeeder extends \Illuminate\Database\Seeder
{
	public function run() {
		// attach vendor to state college zone temp
		factory(App\Models\User::class, 1)->create()->each(function(\App\Models\User $u) {
			$zone = \App\Models\DeliveryZone::where(['name' => 'State College'])->first();
			$u->vendor()->save(factory(\App\Models\Vendor::class)->make(['delivery_zone_id' => $zone->id]));
			$vendor = $u->vendor;

			// add products to this vendor
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

		// 10 vendors
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

			// create some hours entries
			for ($i = 0; $i < 5; $i++) {
				$vendor->hours()->save(factory(\App\Models\VendorStoreHours::class)->make());
			}

			// create some override hours entries
			for ($i = 0; $i < 3; $i++) {
				$vendor->overrideHours()->save(factory(\App\Models\OverrideVendorStoreHours::class)->make());
			}

			// create the settings entry
			$vendor->settings()->save(factory(App\Models\VendorSetting::class)->make());
		});
	}
}