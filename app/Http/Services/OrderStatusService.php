<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/13/16
 * Time: 4:22 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderStatusInterface;

class OrderStatusService extends BaseService
{
	public function __construct(OrderStatusInterface $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * @param $data
	 */
	public function update(array $data) {
		$success = $this->repo->update($data);
		return $success;
	}
}