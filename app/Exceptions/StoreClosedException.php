<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 8/3/16
 * Time: 3:38 PM
 */

namespace App\Exceptions;


class StoreClosedException extends APIException
{
	public function __construct($message = 'You are not authorized to perform this request.', $code = 400)
	{
		parent::__construct($message, $code);
	}
}