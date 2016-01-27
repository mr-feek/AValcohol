<?php

namespace App\Models\Repositories;

use App\Models\Entities\User;
use App\Models\Repositories\Interfaces\UserInterface;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/24/16
 * Time: 11:55 PM
 */
class UserRepository extends BaseRepository implements UserInterface
{

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	public function getUserById($id)
	{
		return $this->model = User::findOrFail($id);
	}

	public function create($data) {
		$user = User::create($data);
		// ensure this property is set..
		$user->mvp_user = true;
		$user->save();

		return $user;
	}

	public function update(User $model, $data) {
		return $this->model = $model->update($data);
	}

	public function enforceUpdatePermissions($data) {
		// to do
	}

	public function enforceGetPermissions($data) {
		// to do
	}
}