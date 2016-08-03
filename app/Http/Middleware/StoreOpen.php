<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Exceptions\StoreClosedException;
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
			//throw new StoreClosedException($service->reasonForStoreClosure());
			// this is easier to relay to the front end that the store is closed, rather than throwing an exception with a message
			return response()->json([
				'success' => false,
				'message' => $service->reasonForStoreClosure(),
				'isClosed' => true,
			]);
		}
		
        return $next($request);
    }
}
