<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:34 PM
 */

namespace App\Models\Repositories\Interfaces;

use App\Models\Entities\User;
use App\Models\Entities\UserAddress;
use App\Models\Entities\Order;

interface OrderInterface
{
	public function createOrder(User $user, UserAddress $address, $products, $data);
	public function chargeUserForOrder(User $user, Order $order, $stripe_token);
}