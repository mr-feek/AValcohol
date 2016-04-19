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
class APIException extends \Exception
{
	public function __construct($message = '', $code = 400)
	{
		$this->message = $message;
		$this->code = $code;
	}
}