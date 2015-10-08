<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/7/15
 * Time: 12:22 PM
 */

namespace Controller;


class EmailController
{
	public static function sendEmail($to, $subject, $message, $headers) {
		$sent = mail($to, $subject, $message, $headers);

		$data = array(
			'success' => $sent
		);

		return $data;
	}
}