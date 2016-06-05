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
		// 10 users
		factory(App\Models\User::class, 10)->create()->each(function(\App\Models\User $u) {
			$u->address()->save(factory(App\Models\UserAddress::class)->make());
			$u->profile()->save(factory(\App\Models\UserProfile::class)->make());
		});

	    // 2 admins
	    factory(App\Models\User::class, 2)->create()->each(function(\App\Models\User $u) {
		    $u->address()->save(factory(App\Models\UserAddress::class)->make());
		    $u->profile()->save(factory(\App\Models\UserProfile::class)->make());
		    $u->roles()->attach(\App\Models\Role::find(1));
	    });
    }
}
