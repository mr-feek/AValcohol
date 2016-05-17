<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	protected $policies = [
		Order::class => OrderPolicy::class
	];
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Boot the authentication services for the application.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Here you may define how you wish users to be authenticated for your Lumen
		// application. The callback which receives the incoming request instance
		// should return either a User instance or null. You're free to obtain
		// the User instance via an API token or any other method necessary.

		/* IS THIS USED??? OR JWT JAWN INSTEAD? */
		$this->app['auth']->viaRequest('api', function ($request)
		{
			return \App\Models\User::where('email', $request->input('email'))->first();
		});

		$this->registerPolicies();
	}

	private function registerPolicies() {
		foreach ($this->policies as $key => $value) {
			Gate::policy($key, $value);
		}
	}
}