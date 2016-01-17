<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Http\Traits\DeliveryHoursTrait;
use Closure;

class DeliveryHours
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
		if (!$this->isOpenNow()) {
			throw new APIException($this->closedMessage);
		}

        return $next($request);
    }
}
