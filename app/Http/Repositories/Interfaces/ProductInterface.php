<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:56 PM
 */

namespace App\Http\Repositories\Interfaces;

use App\Models\Vendor;

interface ProductInterface {
	public function getById($id);
}