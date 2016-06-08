<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/22/16
 * Time: 1:36 AM
 */

namespace App\Http\Repositories\Interfaces;

interface OrderDeliveryDetailsInterface
{
	public function create(array $data);
	public function get(int $id);
}