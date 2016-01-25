<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Repositories\UserRepository;
use App\Models\Repositories\UserAddressRepository;
use App\Models\Repositories\BlacklistedAddressRepository;

use App\Models\Services\UserService;
use App\Models\Services\UserAddressService;
use App\Models\Services\BlacklistedAddressService;

use App\Models\Entities\User;
use App\Models\Entities\UserAddress;
use App\Models\Entities\BlacklistedAddress;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind('App\Models\Repositories\Interfaces\UserInterface', function($app)
		{
			return new UserRepository(new User());
		});
		$this->app->bind('UserService', function($app)
		{
			return new UserService($app->make('Repositories\Interfaces\UserInterface'));
		});

		$this->app->bind('App\Models\Repositories\Interfaces\UserAddressInterface', function($app)
		{
			return new UserAddressRepository(new UserAddress());
		});
		$this->app->bind('UserAddressService', function($app)
		{
			return new UserAddressService($app->make('Repositories\Interfaces\UserAddressInterface'));
		});

		$this->app->bind('App\Models\Repositories\Interfaces\BlacklistedAddressInterface', function($app)
		{
			return new BlacklistedAddressRepository(new BlacklistedAddress());
		});
		$this->app->bind('BlacklistedAddressService', function($app)
		{
			return new BlacklistedAddressService($app->make('Repositories\Interfaces\BlacklistedAddressInterface'));
		});
	}
}
