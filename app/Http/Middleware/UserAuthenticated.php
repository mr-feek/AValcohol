<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/17/16
 * Time: 6:36 PM
 */

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class UserAuthenticated extends BaseMiddleware
{
	/**
	 * Essentially overrides jwt.auth in order to return 401 for no token found instead of 400
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		try {
			$this->authenticate($request);
		} catch (BadRequestHttpException $e) {
			// no token found
			throw new UnauthorizedHttpException('UserAuthenticated', $e->getMessage(), $e, 401); // 401 === unauthorized
		}
		
		return $next($request);
	}
}