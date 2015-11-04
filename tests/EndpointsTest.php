<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/3/15
 * Time: 5:09 PM
 */

class EndpointsTest extends LocalWebTestCase {

	public function setUp() {
		$this->app  = $this->getSlimInstance();
	}

	public function testGetAllUsers() {
		$response = $this->get('/user/all');
		$user = json_decode($response)[0]; // pluck first user
		$this->assertObjectHasAttribute('email', $user); // ensure this is a user object
		$this->assertEquals($this->app->response->getStatus(), 200);
	}
}