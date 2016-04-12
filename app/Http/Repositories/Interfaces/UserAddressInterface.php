<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Http\Repositories\Interfaces;

use App\Models\User;
use App\Models\UserAddress;

interface UserAddressInterface {
	public function create(User $user, $data);
	public function getById($id);
	public function update(UserAddress $model, $id);
}