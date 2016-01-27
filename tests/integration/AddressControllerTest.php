<?php

use App\Models\Entities\UserAddress;
use App\Models\Entities\BlacklistedAddress;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/17/16
 * Time: 3:08 PM
 */
class AddressControllerTest extends TestCase
{
	use \Illuminate\Foundation\Testing\DatabaseTransactions;

	public function testCanCreateAddress() {
		$address = new UserAddress();
		$address->city = 'State College';
		$address->state = 'PA';
		$address->zipcode = '16801';
		$address->street = '810 Walnut Street';

		$data = $address->toArray();
		$data['user']['id'] = 1;

		$this->post('/address', $data);

		$this->seeJson([
			'success' => true,
			'city' => $address->city,
			'state' => $address->state,
			'zipcode' => $address->zipcode,
			'street' => $address->street,
			'user_id' => 1
		]);
	}

	public function testBlacklistOrder() {
		//
	}

	public function testDontCreateAddressIfBlacklisted() {
		$blacklisted = new BlacklistedAddress();
		$blacklisted->street = 'backstreets back, alright!';
		$blacklisted->city = 'blacklisted city town area';
		$blacklisted->state = 'blacklist, USA';
		$blacklisted->zipcode = '16801';
		$blacklisted->reason = 1;
		$blacklisted->save();

		$address = new UserAddress();
		$address->street = $blacklisted->street;
		$address->city = $blacklisted->city;
		$address->state = $blacklisted->state;
		$address->zipcode = $blacklisted->zipcode;
		// don't save here.

		$data = $address->toArray();
		$data['user'] = array('id' => 1);

		$this->post('/address', $data);

		$this->seeJson([
			'success' => false,
			'message' => 'We\'re sorry, but at this time we cannot deliver to fraternities'
		]);
	}

	/*
	public function testDontCreateAddressIfNotInDeliveryZone() {
		$zip = 11111;
		$address = factory(\App\Models\Entities\UserAddress::class)->create(['zipcode' => $zip, 'user_id' => 1]);

		$data = $address->toArray();
		$data['user'] = array('id' => $data['user_id']);
		unset($data['user_id']);

		$this->post('/address', $data);
		$this->seeJson([
			'success' => false,
			'message' => "We're sorry, but at this time we can only deliver to the 16801 area"
		]);
	}
	*/

	public function testUserCannotUpdateOtherUsersAddress() {
		$address = UserAddress::find(1);
		$address->user_id = $address->user_id + 1;

		$data = $address->toArray();

		$this->put('/address/' . $address->id, $data);

		$this->seeJson([
			'success' => false,
			'message' => 'You do not have permission to access this resource.'
		]);
	}
}