<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 5:54 PM
 */

namespace App\Jobs\Mailchimp;

use App\Models\User;

class AddUserToMailchimp extends MailchimpJob
{

	/**
	 * @var User
	 */
	private $user;

	/**
	 * AddUserToMailchimp constructor.
	 * @param User $user
	 */
	public function __construct(User $user) {
		parent::__construct();
		$this->user = $user;
	}

	public function handle() {
		parent::guard();
		$this->mailchimp->post('lists/' . self::LIST_ID . '/members', [
			'email_address' => $this->user->email,
			'merge_fields' => [
				'FIRST_NAME' => $this->user->profile->first_name,
				'LAST_NAME' => $this->user->profile->last_name,
			],
			'interests' => [
				self::SOFT_LAUNCH_INTEREST_ID => true // soft launch
			],
			'status' => 'subscribed'
		]);
	}
}