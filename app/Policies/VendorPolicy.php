<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 10:17 PM
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy extends BasePolicy
{
	public function get(User $user, Vendor $vendor) {
		return $user->vendor->id === $vendor->id;
	}
}