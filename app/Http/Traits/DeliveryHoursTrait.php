<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/16/16
 * Time: 8:13 PM
 */

namespace App\Http\Traits;

use DateTime;


trait DeliveryHoursTrait
{
	protected $closedMessage = "Sorry! At this time we can only accept orders from 6PM to 2AM.";

	public $openTime = "18:00"; // 6 pm
	public $closeTime = "01:59"; // 2 am

	/**
	 * @param $time formatted date('h:i');
	 * @return bool
	 */
	public function isOpenAt($time) {
		return $this->isBetween($this->openTime, $this->closeTime, $time);
	}

	public function isOpenNow() {
		// developers can force the store to be open for testing purposes, so check env and toggle
		$env = \Dotenv::findEnvironmentVariable('APP_ENV');
		$devForcedOpen = \Dotenv::findEnvironmentVariable('STORE_OPEN');
		if ($env == 'local' && $devForcedOpen == true) {
			return true;
		}

		// normal opp
		$currentTime = date('G:i');
		return $this->isBetween($this->openTime, $this->closeTime, $currentTime);
	}

	/**
	 * Thanks to http://stackoverflow.com/questions/27131527/php-check-if-time-is-between-two-times-regardless-of-date/27134087#27134087
	 * @param $from
	 * @param $till
	 * @param $input
	 * @return bool
	 */
	private function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	}
}