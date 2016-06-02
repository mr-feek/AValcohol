<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:17 PM
 */

namespace App\Http\Repositories;


use App\Http\Filters\AdminOrderFilters;
use App\Http\Repositories\Interfaces\AdminInterface;
use App\Models\Order;

class AdminRepository extends BaseRepository implements AdminInterface
{
	/**
	 * Applies the given array as filters to the order query
	 * @param array $data
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 * @internal param array $filters
	 */
	public function searchOrders(array $data)
	{
		$filters = new AdminOrderFilters($data);
		$orders = Order::filter($filters)->with(['status', 'user.profile', 'products', 'address'])->get();
		return $orders;
	}
}