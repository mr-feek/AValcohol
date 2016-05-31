<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Repositories\Interfaces\UserInterface;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Hash;

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

	/**
	 * @param $data
	 * @return static
	 */
	public function create($data) {
		// mvp users do not need a password because they arent actually making accounts
		if (array_key_exists('password', $data)) {
			$rawPassword = $data['password'];
			$hashed = Hash::make($rawPassword);
			$data['password'] = $hashed;
		}

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

	/**
	 * lazy way to add people signing up for news
	 * @param $name
	 * @param $email
	 * @return array|false
	 */
	public function basicAddToMailChimp($name, $email) {
		$result = $this->mailchimp->post('lists/' . self::LIST_ID . '/members', [
			'email_address' => $email,
			'merge_fields' => [
				'FIRST_NAME' => $name
			],
			'interests' => [
				self::SOFT_LAUNCH_INTEREST_ID => true // soft launch
			],
			'status' => 'subscribed'
		]);

		return $result;
	}
}