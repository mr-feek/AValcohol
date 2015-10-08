<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/8/15
 * Time: 1:37 PM
 */

namespace Controller;

use Model;


class UserController
{
	public static function addUser($unhashedPassword, $email) {
		$hash = password_hash($unhashedPassword, PASSWORD_BCRYPT);

		$user = Model::factory('User')->create();
		$user->email = $email;
		$user->password = $hash;
		return $user->save();
	}
}