<?php

namespace App\Providers;

use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Validator;
use DateTime;
use App\Models\Vendor;
use App\Http\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserAddressRepository;
use App\Http\Repositories\BlacklistedAddressRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;

use App\Http\Services\UserService;
use App\Http\Services\UserAddressService;
use App\Http\Services\BlacklistedAddressService;
use App\Http\Services\OrderService;
use App\Http\Services\ProductService;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\BlacklistedAddress;
use App\Models\Order;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		Validator::extend('isTwentyOne', function($attribute, $value, $parameters, $validator) {
			$from = new DateTime($value);
			$to   = new DateTime('today');
			$age = $from->diff($to)->y;

			return $age >= 21;
		}, 'You must be 21 in order to create an account');

		$this->app->bind('App\Http\Repositories\Interfaces\UserInterface', function($app)
		{
			return new UserRepository(new User(), new MailChimp(env('MAILCHIMP_KEY')));
		});
		$this->app->bind('UserService', function($app)
		{
			return new UserService($app->make('Repositories\Interfaces\UserInterface'));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\UserAddressInterface', function($app)
		{
			return new UserAddressRepository(new UserAddress());
		});
		$this->app->bind('UserAddressService', function($app)
		{
			return new UserAddressService($app->make('Repositories\Interfaces\UserAddressInterface'));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\BlacklistedAddressInterface', function($app)
		{
			return new BlacklistedAddressRepository(new BlacklistedAddress());
		});
		$this->app->bind('BlacklistedAddressService', function($app)
		{
			return new BlacklistedAddressService($app->make('Repositories\Interfaces\BlacklistedAddressInterface'));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\OrderInterface', function($app)
		{
			return new OrderRepository(new Order());
		});
		$this->app->bind('OrderService', function($app)
		{
			return new OrderService($app->make('Repositories\Interfaces\OrderInterface'));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\ProductInterface', function($app)
		{
			return new ProductRepository(new Product());
		});
		$this->app->bind('ProductService', function($app)
		{
			return new ProductService($app->make('Repositories\Interfaces\ProductInterface'));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\VendorInterface', function($app)
		{
			return new VendorRepository(new Vendor());
		});
		$this->app->bind('VendorService', function($app)
		{
			return new VendorService($app->make('Repositories\Interfaces\VendorInterface'));
		});
	}
}
