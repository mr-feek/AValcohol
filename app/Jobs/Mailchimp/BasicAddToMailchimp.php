<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 6:07 PM
 */

namespace App\Jobs\Mailchimp;

class BasicAddToMailchimp extends MailchimpJob
{
	/**
	 * @var
	 */
	private $name;
	/**
	 * @var
	 */
	private $email;

	public function __construct($name, $email)
	{
		$this->name = $name;
		$this->email = $email;
	}
	
	public function handle() {
		parent::guard();
		
		$this->mailchimp->post('lists/' . self::LIST_ID . '/members', [
			'email_address' => $this->email,
			'merge_fields' => [
				'FIRST_NAME' => $this->name
			],
			'interests' => [
				self::SOFT_LAUNCH_INTEREST_ID => true // soft launch
			],
			'status' => 'subscribed'
		]);
	}
}