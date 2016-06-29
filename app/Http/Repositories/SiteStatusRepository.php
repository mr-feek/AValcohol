<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 11:46 AM
 */

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\SiteStatusInterface;
use App\Models\SiteStatus;
use DateTime;

class SiteStatusRepository extends BaseRepository implements SiteStatusInterface
{
	public $closedMessage = '';
	public $openTime = "18:00"; // 6 pm
	public $closeTime = "01:59"; // 2 am

	public function __construct()
	{
		$this->model = SiteStatus::all()->first();
	}

	/**
	 * @param bool $online
	 * @return mixed
	 */
	public function setStoreStatus(bool $online) {
		$this->model->online = $online;
		return $this->model;
	}

	/**
	 * developers can force the store to be open for testing purposes, so check env and toggle
	 * @return bool
	 */
	public function devForcedOpen() {
		if (env('FORCE_STORE_OPEN') === true) {
			return true;
		}
		return false;
	}

	/**
	 * admins can disable the store via the dashboard
	 * @return bool
	 */
	public function checkIfAdminHasClosedStore() {
		if (!$this->model->online) {
			$this->closedMessage = 'Due to unforeseen circumstances our store has been temporarily closed. Please check back soon.';
			return true;
		}
		return false;
	}

	/**
	 * checks against the default delivery hours if the store is open. returns true if store is open.
	 * @return bool
	 */
	public function checkDeliveryHours() {
		$currentTime = date('G:i');
		$open = $this->isOpenAt($currentTime);
		if (!$open) {
			$this->closedMessage = 'At this time our delivery service is from 6PM to 2AM.';
		}
		return $open;
	}

	/**
	 * @param $time formatted date('h:i');
	 * @return bool
	 */
	public function isOpenAt($time) {
		return $this->isBetween($this->openTime, $this->closeTime, $time);
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