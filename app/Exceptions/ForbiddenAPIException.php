<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/16/16
 * Time: 4:33 PM
 */

namespace App\Exceptions;


class ForbiddenAPIException extends APIException
{
	public function __construct($message = 'You are not authorized to perform this request.', $code = 403)
	{
		parent::__construct($message, $code);
	}
}