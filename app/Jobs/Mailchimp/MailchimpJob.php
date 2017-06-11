<?php

namespace App\Jobs\Mailchimp;

use App\Jobs\Job;
use DrewM\MailChimp\MailChimp;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 6:07 PM
 */
class MailchimpJob extends Job
{
	const LIST_ID = 'e2c9eed2ca';
	const SOFT_LAUNCH_INTEREST_ID = 'f7cb7e6ba5';
	protected $mailchimp;

	public function __construct()
	{
		$this->mailchimp = new MailChimp(env('MAILCHIMP_KEY'));
	}

	protected function guard() {
		if (app()->environment() !== 'production') {
			$this->delete();
			throw new \Exception('throwing exception to delete add user to mailchimp job since not in production');
		}
	}
}