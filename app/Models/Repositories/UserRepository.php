<?php

namespace App\Models\Repositories;

use App\Models\Entities\User;
use App\Models\Repositories\Interfaces\UserInterface;
use DrewM\MailChimp\MailChimp;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/24/16
 * Time: 11:55 PM
 */
class UserRepository extends BaseRepository implements UserInterface
{
	protected $mailchimp;
	const LIST_ID = 'e2c9eed2ca';
	const SOFT_LAUNCH_INTEREST_ID = 'f7cb7e6ba5';

	public function __construct(User $user, MailChimp $mailchimp)
	{
		$this->model = $user;
		$this->mailchimp = $mailchimp;
	}

	public function getUserById($id)
	{
		return $this->model = User::findOrFail($id);
	}

	public function create($data) {
		$user = User::create($data);
		// ensure this property is set..
		$user->mvp_user = true;
		$user->save();

		return $user;
	}

	public function attachProfile(User $user, $data)
	{
		$user->profile()->create($data);
		return $user;
	}

	public function update(User $model, $data) {
		return $this->model = $model->update($data);
	}

	public function enforceUpdatePermissions($data) {
		// to do
	}

	public function enforceGetPermissions($data) {
		// to do
	}

	/**
	 * //todo: migrate this to be processed by queue
	 * @param User $user
	 */
	public function addToMailChimp(User $user)
	{
		$result = $this->mailchimp->post('lists/' . self::LIST_ID . '/members', [
			'email_address' => $user->email,
			'merge_fields' => [
				'FIRST_NAME' => $user->profile->first_name,
				'LAST_NAME' => $user->profile->last_name,
			],
			'interests' => [
				self::SOFT_LAUNCH_INTEREST_ID => true // soft launch
			],
			'status' => 'subscribed'
		]);
	}
}