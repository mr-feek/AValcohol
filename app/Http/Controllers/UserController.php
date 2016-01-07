<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/26/15
 * Time: 7:01 PM
 */

namespace App\Http\Controllers;


use App\Http\Traits\UserTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
	use UserTrait;

	public function create(Request $request) {
		$user = $this->createUser($request->input());

		return response()->json([
			'success' => true,
			'user' => $user
		]);
	}

	public function update(Request $request) {
		$user = $this->updateUser($request->input());
		return response() ->json([
			'success' => true,
			'user' => $user
		]);
	}
}