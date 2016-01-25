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
		factory(App\Models\Entities\User::class, 20)->create()->each(function($u) {
			$u->addresses()->save(factory(App\Models\Entities\UserAddress::class)->make());
		});
    }
}
