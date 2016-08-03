<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Http\Services\SiteStatusService;
use App\Http\Services\VendorHoursService;
use Closure;

class StoreOpen
{

	/**
	 * Handle an incoming request. This middleware checks if we are currently accepting new orders depending on the time of day
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 * @throws APIException
	 * @internal param SiteStatusService $service
	 */
    public function handle($request, Closure $next)
    {
	    $service = new SiteStatusService(app()->make('App\Http\Repositories\Interfaces\SiteStatusInterface'), new VendorHoursService());

		if (!$service->isOpenNow($request->input())) {
			throw new APIException($service->reasonForStoreClosure());
		}
		
        return $next($request);
    }
}
