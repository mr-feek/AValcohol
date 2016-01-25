<?php

namespace App\Models\Repositories\Interfaces;

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
}