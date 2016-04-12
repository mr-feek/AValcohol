<?php

namespace App\Http\Repositories\Interfaces;

use App\Models\User;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/24/16
 * Time: 11:54 PM
 */
interface UserInterface
{
	public function getUserById($id);
	public function create($data);
	public function attachProfile(User $user, $data);
	public function addToMailChimp(User $user);
}