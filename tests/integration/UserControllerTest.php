<?php

use App\Models\UserProfile;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/7/16
 * Time: 1:02 PM
 */
class UserControllerTest extends TestCase
{
	public function testCreateUser() {
		$user = factory(User::class)->make();
		$profile = factory(UserProfile::class)->make();

		$user->date_of_birth = $profile->date_of_birth;
		$user->first_name = $profile->first_name;
		$user->last_name = $profile->last_name;
		$user->phone_number = $profile->phone_number;

		$this->post('/user', $user->toArray());

		$this->seeJsonStructure([
			'success',
			'user' => [
				'id',
				'email'
			]
		]);

		$this->seeJsonContains([
			'success' => true,
			'email' => $user->email
		]);

		$user_id = json_decode($this->response->getContent())->user->id;

		$this->seeInDatabase('users', [
			'id' => $user_id,
			'email' => $user->email
		]);

		$this->seeInDatabase('user_profiles', [
			'date_of_birth' => $user->date_of_birth,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'phone_number' => $user->phone_number,
			'user_id' => $user_id
		]);
	}

	public function testCannotCreateUserIfNotTwentyOne() {
		$user = factory(App\Models\User::class)->make();
		$profile = factory(\App\Models\UserProfile::class)->make();

		$user->first_name = $profile->first_name;
		$user->last_name = $profile->last_name;
		$user->phone_number = $profile->phone_number;

		$today   = new DateTime('today');
		$twenty = $today->sub(new DateInterval('P20Y'));
		$twenty = $twenty->format('Y-m-d');
		$user->date_of_birth = $twenty;

		$this->post('/user', $user->toArray());

		$this->seeJsonEquals([
			'date_of_birth' => ['You must be 21 in order to create an account']
		]);
	}
}