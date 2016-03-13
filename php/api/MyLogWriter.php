<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/13/16
 * Time: 6:39 PM
 */

namespace api;

class MyLogWriter
{

	protected $logpath = './error.log';

	/**
	 * @var resource
	 */
	protected $resource;

	/**
	 * Constructor
	 * @throws \InvalidArgumentException If invalid resource
	 */
	public function __construct()
	{
		$resource = fopen($this->logpath, 'a');
		if (!is_resource($resource)) {
			throw new \InvalidArgumentException('Cannot create LogWriter. Invalid resource handle.');
		}
		$this->resource = $resource;
	}

	/**
	 * Write message
	 * @param  mixed     $message
	 * @param  int       $level
	 * @return int|bool
	 */
	public function write($message, $level = null)
	{
		// send email with error message to me
		$to      = 'feek@avalcohol.com';
		$from = 'server?';

		$subject = 'Error!';
		$headers = //'From: ' . $from . "\r\n" .
			'Reply-To: ' . $from . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		$sent = mail($to, $subject, $message, $headers);


		return fwrite($this->resource, (string) $message . PHP_EOL);
	}
}