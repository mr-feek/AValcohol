<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/7/16
 * Time: 3:07 AM
 */

namespace App\Http\Domain\OrderDeliveryDetails\Interfaces;

interface PhotoManagerInterface
{
	public function put(string $data);
	public function get();
}