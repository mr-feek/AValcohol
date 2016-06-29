<?php

namespace App\Providers;

use App\Http\Domain\OrderDeliveryDetails\PhotoManager;

use App\Http\Repositories\SiteStatusRepository;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Validator;
use DateTime;

use Illuminate\Support\ServiceProvider;

use App\Http\Repositories\UserRepository;
use App\Http\Repositories\VendorRepository;
use App\Http\Repositories\UserAddressRepository;
use App\Http\Repositories\BlacklistedAddressRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\OrderStatusRepository;
use App\Http\Repositories\OrderDeliveryDetailsRepository;

use App\Models\Vendor;
use App\Models\OrderDeliveryDetail;
use App\Models\OrderStatus;
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
	    /*
	    |--------------------------------------------------------------------------
	    | validators
	    |--------------------------------------------------------------------------
	    |
	    | validators to be utilized throughout our app
	    |
	    */
		Validator::extend('isTwentyOne', function($attribute, $value, $parameters, $validator) {
			$from = new DateTime($value);
			$to   = new DateTime('today');
			$age = $from->diff($to)->y;

			return $age >= 21;
		}, 'You must be 21 in order to create an account');

	    /*
	    |--------------------------------------------------------------------------
	    | singleton bindings
	    |--------------------------------------------------------------------------
	    |
	    | dope context
	    |
	    */

	    $this->app->singleton('filesystem', function ($app) {
		    return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
	    });

	    $this->app->singleton('mailer', function ($app) {
		    $app->configure('services');
		    return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
	    });

	    /*
	    |--------------------------------------------------------------------------
	    | misc
	    |--------------------------------------------------------------------------
	    |
	    | change when we have more in here...
	    |
	    */

	    $this->app->bind('App\Http\Domain\OrderDeliveryDetails\Interfaces\PhotoManagerInterface', function($app) {
		    return new PhotoManager();
	    });

	    /*
        |--------------------------------------------------------------------------
        | repository bindings
        |--------------------------------------------------------------------------
        |
        | dope context
        |
        */

		$this->app->bind('App\Http\Repositories\Interfaces\UserInterface', function($app)
		{
			return new UserRepository(new User(), new MailChimp(env('MAILCHIMP_KEY')));
		});

		$this->app->bind('App\Http\Repositories\Interfaces\UserAddressInterface', function($app)
		{
			return new UserAddressRepository(new UserAddress());
		});

		$this->app->bind('App\Http\Repositories\Interfaces\BlacklistedAddressInterface', function($app)
		{
			return new BlacklistedAddressRepository(new BlacklistedAddress());
		});

		$this->app->bind('App\Http\Repositories\Interfaces\OrderInterface', function($app)
		{
			return new OrderRepository(new Order());
		});

		$this->app->bind('App\Http\Repositories\Interfaces\ProductInterface', function($app)
		{
			return new ProductRepository(new Product());
		});

		$this->app->bind('App\Http\Repositories\Interfaces\VendorInterface', function($app)
		{
			return new VendorRepository(new Vendor());
		});

		$this->app->bind('App\Http\Repositories\Interfaces\AdminInterface', function($app)
		{
			return new AdminRepository();
		});

		$this->app->bind('App\Http\Repositories\Interfaces\OrderStatusInterface', function($app)
		{
			return new OrderStatusRepository(new OrderStatus());
		});

	    $this->app->bind('App\Http\Repositories\Interfaces\SiteStatusInterface', function($app)
	    {
		    return new SiteStatusRepository();
	    });

		$this->app->bind('App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface', function($app)
		{
			return new OrderDeliveryDetailsRepository(new OrderDeliveryDetail(), $app->make('App\Http\Domain\OrderDeliveryDetails\Interfaces\PhotoManagerInterface'));
		});
	}
}
