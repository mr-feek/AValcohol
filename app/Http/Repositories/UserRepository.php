<?php

namespace App\Http\Repositories;

use App\Exceptions\InvalidPasswordException;
use App\Jobs\Mailchimp\AddUserToMailchimp;
use App\Jobs\Mailchimp\BasicAddToMailchimp;
use App\Models\User;
use App\Http\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/24/16
 * Time: 11:55 PM
 */
class UserRepository extends BaseRepository implements UserInterface
{

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	/**
	 * returns user with profile and roles preloaded
	 * @param $id
	 * @return User
	 */
	public function getUserById($id)
	{
		return $this->model = User::with(['profile', 'roles'])->findOrFail($id);
	}

	/**
	 * @param $data
	 *
	 * @return static
	 * @throws InvalidPasswordException
	 */
	public function create($data) {
		// mvp users do not need a password because they arent actually making accounts
		if (array_key_exists('password', $data)) {
			$rawPassword = $data['password'];

			if (strlen($rawPassword) < 7) {
				throw new InvalidPasswordException();
			}
			
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
	 * @param User $user
	 */
	public function addToMailChimp(User $user)
	{
		dispatch(new AddUserToMailchimp($user));
	}

	/**
	 * lazy way to add people signing up for news
	 * @param $name
	 * @param $email
	 */
	public function basicAddToMailChimp(string $name, string $email) {
		dispatch(new BasicAddToMailchimp($name, $email));
	}
}
