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
	public function get(User $user, Order $order) {
		if ($user->isVendor()) {
			return $user->vendor->id === $order->vendor_id;
		}

		return $user->id === $order->user_id;
	}
}