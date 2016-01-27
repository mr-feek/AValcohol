<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Models\Repositories\Interfaces;

use App\Models\Entities\User;
use App\Models\Entities\UserAddress;

interface UserAddressInterface {
	public function create(User $user, $data);
	public function getById($id);
	public function update(UserAddress $model, $id);
}