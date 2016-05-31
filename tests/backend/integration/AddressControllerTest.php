<?php

use App\Models\UserAddress;
use App\Models\BlacklistedAddress;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/17/16
 * Time: 3:08 PM
 */
class AddressControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function testCanCreateAddress() {
		$address = new UserAddress();
		$address->city = 'State College';
		$address->state = 'PA';
		$address->zipcode = '16801';
		$address->street = '810 Walnut Street';
		$address->location = [
			'longitude' => 0,
			'latitude' => 1
		];

		$data = $address->toArray();
		$data['user']['id'] = 1;

		$this->post('/address', $data);

		$this->seeJson([
			'success' => true,
			'city' => $address->city,
			'state' => $address->state,
			'zipcode' => $address->zipcode,
			'street' => $address->street,
			'location' => $address->location,
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
		$blacklisted->delivery_zone_id = 1; // temp
		$blacklisted->save();

		$address = new UserAddress();
		$address->street = $blacklisted->street;
		$address->city = $blacklisted->city;
		$address->state = $blacklisted->state;
		$address->zipcode = $blacklisted->zipcode;
		$address->location = [
			'latitude' => 0,
			'longitude' => 0
		];
		// don't save here.

		$data = $address->toArray();
		$data['user'] = array('id' => 1);

		$this->post('/address', $data);

		$this->seeJson([
			'success' => false,
			'message' => 'We\'re sorry, but at this time we cannot deliver to fraternities'
		]);
	}


	public function testDontCreateAddressIfNotInDeliveryZone() {

	}

	public function testGetDeliveryZoneID() {
		$location = [
			'latitude' => 0,
			'longitude' => 0
		];

		$this->get('address/delivery_zone?latitude=' . $location['latitude'] . '&longitude=' . $location['longitude']);
		$response = json_decode($this->response->getContent());
		$this->assertNotNull($response->delivery_zone_id, 'No delivery zone contains point 0,0. This could be a flaky test, fix if so by creating a delivery zone containing this point beforehand.');
	}

/*
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
*/
}