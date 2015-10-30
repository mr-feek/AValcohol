<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/11/15
 * Time: 6:49 PM
 */

namespace Controller;

class UserController extends BaseController
{
	public static function addUser($unhashedPassword, $email) {
		$hash = password_hash($unhashedPassword, PASSWORD_BCRYPT);

		$user = Model::factory('User')->create();
		$user->email = $email;
		$user->password = $hash;
		return $user->save();
	}
}