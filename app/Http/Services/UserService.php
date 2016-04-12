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
	protected $orderRepo;

	/**
	 * UserService constructor.
	 * Loads our $userRepo with the actual Repo associated with our userInterface
	 * @param userInterface $userRepo
	 * @param OrderInterface $orderRepo
	 * @internal param UserAddressInterface $addressRepo
	 */
	public function __construct(UserInterface $userRepo, OrderInterface $orderRepo)
	{
		$this->repo = $userRepo;
		$this->orderRepo = $orderRepo;
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

		$this->repo->addToMailChimp($user);

		return $user;
	}

	public function update($data) {
		$user = $this->getUser($data['id']);
		$this->repo->enforceUpdatePermissions($data);
		return $this->repo->update($user, $data);
	}
}