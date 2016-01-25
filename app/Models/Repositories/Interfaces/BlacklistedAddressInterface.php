<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:35 AM
 */

namespace App\Models\Repositories\Interfaces;

interface BlacklistedAddressInterface {
	public function get($data);
}