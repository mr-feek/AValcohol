<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:34 PM
 */

namespace App\Http\Repositories\Interfaces;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;

interface OrderInterface
{
	public function createOrder(User $user, UserAddress $address, $products, $data);
	public function chargeUserForOrder(User $user, Order $order, $stripe_token);
	public function authorizeChargeOnCard(Order $order, $stripe_token);
}