<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/16/16
 * Time: 4:33 PM
 */

namespace App\Exceptions;

/**
 * Usecase is for model::findOrFail() but with query builder
 * Class NoCollectionResultsAPIException
 * @package App\Exceptions
 */
class NoCollectionResultsAPIException extends APIException
{
	public function __construct($message = 'No Results Found For Collection', $code = 404)
	{
		parent::__construct($message, $code);
	}
}