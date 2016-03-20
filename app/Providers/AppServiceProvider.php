<?php

namespace App\Providers;

use App\Models\Entities\Vendor;
use App\Models\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

use App\Models\Repositories\UserRepository;
use App\Models\Repositories\UserAddressRepository;
use App\Models\Repositories\BlacklistedAddressRepository;
use App\Models\Repositories\OrderRepository;
use App\Models\Repositories\ProductRepository;

use App\Models\Services\UserService;
use App\Models\Services\UserAddressService;
use App\Models\Services\BlacklistedAddressService;
use App\Models\Services\OrderService;
use App\Models\Services\ProductService;

use App\Models\Entities\User;
use App\Models\Entities\UserAddress;
use App\Models\Entities\BlacklistedAddress;
use App\Models\Entities\Order;
use App\Models\Entities\Product;

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

		$this->app->bind('App\Models\Repositories\Interfaces\OrderInterface', function($app)
		{
			return new OrderRepository(new Order());
		});
		$this->app->bind('OrderService', function($app)
		{
			return new OrderService($app->make('Repositories\Interfaces\OrderInterface'));
		});

		$this->app->bind('App\Models\Repositories\Interfaces\ProductInterface', function($app)
		{
			return new ProductRepository(new Product());
		});
		$this->app->bind('ProductService', function($app)
		{
			return new ProductService($app->make('Repositories\Interfaces\ProductInterface'));
		});

		$this->app->bind('App\Models\Repositories\Interfaces\VendorInterface', function($app)
		{
			return new VendorRepository(new Vendor());
		});
		$this->app->bind('VendorService', function($app)
		{
			return new VendorService($app->make('Repositories\Interfaces\VendorInterface'));
		});
	}
}
