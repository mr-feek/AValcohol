<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Http\Services\SiteStatusService;
use Closure;

class StoreOpen
{

	/**
	 * Handle an incoming request. This middleware checks if we are currently accepting new orders depending on the time of day
	 * @param $request
	 * @param Closure $next
	 * @param SiteStatusService $service
	 * @return mixed
	 * @throws APIException
	 */
    public function handle($request, Closure $next)
    {
	    $service = new SiteStatusService(app()->make('App\Http\Repositories\Interfaces\SiteStatusInterface'));

		if (!$service->isOpenNow()) {
			throw new APIException($service->reasonForStoreClosure());
		}
		
        return $next($request);
    }
}
