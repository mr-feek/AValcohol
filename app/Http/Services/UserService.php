<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderInterface;
use App\Http\Repositories\Interfaces\UserInterface;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 12:03 AM
 */
class UserService extends BaseService
{

	/**
	 * UserService constructor.
	 * Loads our $userRepo with the actual Repo associated with our userInterface
	 * @param userInterface $userRepo
	 */
	public function __construct(UserInterface $userRepo)
	{
		$this->repo = $userRepo;
	}

	public function getUser($id) {
		$this->repo->enforceGetPermissions($id);
		return $this->repo->getUserById($id);
	}

	public function create($data, $withUserProfile = true) {
		$user = $this->repo->create($data);
		if ($withUserProfile) {
			$user = $this->repo->attachProfile($user, $data);
		}

		if (env('environment') === 'production') {
			$this->repo->addToMailChimp($user);
		}

		return $user;
	}

	public function update($data) {
		$user = $this->getUser($data['id']);
		$this->repo->enforceUpdatePermissions($data);
		return $this->repo->update($user, $data);
	}
}