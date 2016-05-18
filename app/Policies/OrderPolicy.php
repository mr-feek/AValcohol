<?php
namespace App\Policies;

use App\Exceptions\ForbiddenAPIException;
use App\Models\Order;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/16/16
 * Time: 4:58 PM
 */
class OrderPolicy extends BasePolicy
{
	public function vendorGetOrder(User $user, Order $order) {
		return $user->vendor->id === $order->vendor_id;
		/*
		if ($user->vendor->id === $order->vendor_id) {
			return true;
		}

		throw new ForbiddenAPIException();
		*/
	}
}