<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 10:51 AM
 */
class SiteStatusControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;
	
	public function setUp()
	{
		parent::setUp();
		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testForceStoreOffline() {
		$this->post('site/status', ['admin_force_offline' => 1], $this->authHeader);
		$this->seeInDatabase('site_status', ['admin_force_offline' => 1]);

		$response = json_decode($this->response->getContent());
		$this->assertTrue($response->site_status->admin_force_offline);
	}

	public function testRemoveForceStoreOffline() {
		$this->post('site/status', ['admin_force_offline' => 0], $this->authHeader);
		$this->seeInDatabase('site_status', ['admin_force_offline' => 0]);
		$response = json_decode($this->response->getContent());
		$this->assertFalse($response->site_status->admin_force_offline);
	}
}