<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/26/15
 * Time: 7:01 PM
 */

namespace App\Http\Controllers;


use App\Models\User;

class UserController extends Controller
{
	/**
	 * Charges the user the set amount
	 *
	 * @param $user_id
	 * @param $token
	 * @param $amount (pennies)
	 * @return mixed
	 */
	public static function charge($user_id, $token, $amount) {
		$user = User::find($user_id);

		$options = [
			'currency' => 'usd',
			'description' => 'test charge',
			'source' => $token
		];

		return $user->charge($amount, $options);
	}
}