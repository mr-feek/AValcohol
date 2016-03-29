<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:50 PM
 */

namespace App\Models\Repositories\Interfaces;

use App\Models\Entities\Vendor;

interface VendorInterface
{
	public function login($username, $password);
	public function getById($id);
	public function getProducts(Vendor $vendor);
}