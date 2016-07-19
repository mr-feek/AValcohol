<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/4/16
 * Time: 2:58 PM
 */

namespace App\Http\Middleware;


use App\Exceptions\ForbiddenAPIException;
use Closure;
use Illuminate\Support\Facades\Auth;

class HasRole
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @param string $role
	 * @return mixed
	 * @throws ForbiddenAPIException
	 */
	public function handle($request, Closure $next, $role)
	{
		$user = Auth::user();

		// hacky but fine for now
		if ($role === 'vendor') {
			// process differently since this isn't actually stored in the role table for now
			if ($user->isVendor()) {
				return $next($request);
			}

			throw new ForbiddenAPIException();
		}

		if ($user->hasRole($role)) {
			return $next($request);
		}

		throw new ForbiddenAPIException();
	}
}