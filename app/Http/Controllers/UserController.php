<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/26/15
 * Time: 7:01 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services\UserService;

class UserController extends Controller
{

	public function create(Request $request, UserService $service) {
		$this->validate($request, [
			'email' => 'required|email',
			'first_name' => 'required|alpha',
			'last_name' => 'required|alpha',
			'phone_number' => 'required|digits:10'
		]);

		$user = $service->create($request->input());

		return response()->json([
			'success' => true,
			'user' => $user
		]);
	}

	public function update(Request $request, UserService $service) {
		$this->validate($request, [
			'email' => 'email',
			'first_name' => 'alpha',
			'last_name' => 'alpha',
			'phone_number' => 'digits:10'
		]);

		$user = $service->update($request->input());
		return response() ->json([
			'success' => true,
			'user' => $user
		]);
	}

	public function get(UserService $userService, $id) {
		$data = $userService->getUser($id);
		return response()->json([
			'id' => $data->id
		]);
	}
}