<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/13/16
 * Time: 4:26 PM
 */

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\OrderStatusInterface;
use App\Models\OrderStatus;

class OrderStatusRepository extends BaseRepository implements OrderStatusInterface
{
	public function __construct(OrderStatus $orderStatus)
	{
		$this->model = $orderStatus;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function update(array $data) : bool {
		return $this->model->find($data['order_id'])->update($data);
	}
}