<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Http\Traits\DeliveryHoursTrait;
use App\Models\SiteStatus;
use Closure;

class StoreOpen
{
	use DeliveryHoursTrait;

	/**
	 * Handle an incoming request. This middleware checks if we are currently accepting new orders depending on the time of day
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 * @throws APIException
	 */
    public function handle($request, Closure $next)
    {
	    // check if admin has turned store off
	    $status = SiteStatus::all()->first();
	    if (!$status->online) {
		    throw new APIException('Sorry! Due to unforeseen circumstances our store has been temporarily closed. Please check back soon.');
	    }

	    // delivery hours trait (obv should be refactored)
		if (!$this->isOpenNow()) {
			throw new APIException($this->closedMessage);
		}

        return $next($request);
    }
}
