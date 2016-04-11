<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:56 PM
 */

namespace App\Models\Repositories\Interfaces;

use App\Models\Entities\Vendor;

interface ProductInterface {
	public function getById($id);
}