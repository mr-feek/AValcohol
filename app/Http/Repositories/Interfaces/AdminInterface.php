<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/12/16
 * Time: 3:16 PM
 */

namespace App\Http\Repositories\Interfaces;

interface AdminInterface
{
	public function searchOrders(array $filters);
}
