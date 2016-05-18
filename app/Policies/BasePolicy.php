<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/16/16
 * Time: 5:01 PM
 */

namespace App\Policies;

use App\Exceptions\ForbiddenAPIException;

class BasePolicy
{

	public function after($user, $ability, $result) {
		if ($result !== true) {
			throw new ForbiddenAPIException();
		}
	}
}