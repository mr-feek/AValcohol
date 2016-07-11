<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 11:47 AM
 */

namespace App\Http\Repositories\Interfaces;

interface SiteStatusInterface
{
	public function devForcedOpen();
	public function checkIfAdminHasClosedStore();
	public function checkDeliveryHours();
	public function closeStore(bool $online);
	public function get();
}