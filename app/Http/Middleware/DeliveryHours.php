<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use Closure;
use DateTime;

class DeliveryHours
{
	protected $closedMessage = "Sorry! At this time we can only accept orders from 6PM to 2AM.";

	public $openTime = "18:00"; // 6 pm
	public $closeTime = "01:59"; // 2 am


	/**
	 * Handle an incoming request. This middleware checks if we are currently accepting new orders depending on the time of day
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 * @throws APIException
	 */
    public function handle($request, Closure $next)
    {
		$currentTime = date('h:i'); // gets the current hour

		$open = $this->isBetween($this->openTime, $this->closeTime, $currentTime);

		if (!$open) {
			throw new APIException($this->closedMessage);
		}

        return $next($request);
    }

	/**
	 * Thanks to http://stackoverflow.com/questions/27131527/php-check-if-time-is-between-two-times-regardless-of-date/27134087#27134087
	 * @param $from
	 * @param $till
	 * @param $input
	 * @return bool
	 */
	function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	}
}
