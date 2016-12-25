<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/7/16
 * Time: 3:07 AM
 */

namespace App\Http\Domain\OrderDeliveryDetails\Interfaces;

use App\Models\OrderDeliveryDetail;

interface PhotoManagerInterface
{
	public function put(string $data);
	public function get(OrderDeliveryDetail $model);
	public function destroy(OrderDeliveryDetail $model);
}
