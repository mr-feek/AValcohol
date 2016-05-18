<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/4/16
 * Time: 3:22 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
	protected $jwt;

	public function __construct(JWTAuth $jwt)
	{
		$this->jwt = $jwt;
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	function login(Request $request)
	{
		$this->validate($request, [
			'email'    => 'required|email|max:255',
			'password' => 'required',
		]);

		try {
			$credentials = $request->only('email', 'password');

			if (! $token = $this->jwt->attempt($credentials)) {
				return response()->json(['user_not_found'], 404);
			}
		} catch (TokenExpiredException $e) {
			return response()->json(['token_expired'], 500);
		} catch (TokenInvalidException $e) {
			return response()->json(['token_invalid'], 500);
		} catch (JWTException $e) {
			return response()->json(['token_absent' => $e->getMessage()], 500);
		}
		
		return response()->json(compact('token'));
	}
}