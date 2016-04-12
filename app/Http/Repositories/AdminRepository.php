<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:17 PM
 */

namespace App\Http\Repositories;


use App\Http\Repositories\Interfaces\AdminInterface;
use App\Models\Order;

class AdminRepository extends BaseRepository implements AdminInterface
{

	/*
	 * todo: optimize
	 * this could be optimized by querying order table directly
	 */
	public function getOrdersReadyToBePickedUp()
	{
		$orders = Order::whereHas('status', function($query) {
			$query->where([
				['vendor_status', 'accepted'],
				['delivery_status', 'pending'],
				['charge_authorized', true],
				['charge_captured', true]
			]);
		})->with(['status', 'user.profile', 'products'])->get();

		return $orders;
	}

	/*
	 * todo: optimize
	 * this could be optimized by querying order table directly
	 */
	public function getOrdersOutForDelivery()
	{
		$orders = Order::whereHas('status', function($query) {
			$query->where([
				['vendor_status', 'accepted'],
				['delivery_status', 'out-for-delivery'],
				['charge_authorized', true],
				['charge_captured', true]
			]);
		})->with(['status', 'user.profile', 'products'])->get();

		return $orders;
	}
}