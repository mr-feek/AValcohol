<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->singleton('Pusher', function ($app) {
			$app_id = '161104';
			$app_key = 'c644fb6bfa1ca7b73b06';
			$app_secret = '6e5f3cb426a007049e8c';

			return new Pusher($app_key, $app_secret, $app_id);
		});
    }
}
