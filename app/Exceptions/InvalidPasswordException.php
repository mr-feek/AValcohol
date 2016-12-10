<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/1/16
 * Time: 9:55 PM
 */

namespace App\Exceptions;

/**
 * Class APIException
 *
 * When throwing this exception, the response is returned in JSON rather than the rendered page stacktrace jawn. This functionality
 * Is overwritten in Handler.php
 *
 * @package App\Exceptions
 */
class InvalidPasswordException extends \Exception
{
	public function __construct($message = '', $code = 400)
	{
		$message = 'Passwords must be at least 7 characters in length';
		$this->message = $message;
		$this->code = $code;
	}
}
