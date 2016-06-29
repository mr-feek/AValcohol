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

	public function testTurnStoreOffline() {
		$this->post('site/status', ['online' => 0], $this->authHeader);
		$this->seeInDatabase('site_status', ['online' => 0]);
		$this->seeJsonStructure([
			'status' => [
				'created_at',
				'updated_at',
				'online'
			]
		]);
	}

	public function testTurnStoreOnline() {
		$this->post('site/status', ['online' => 1], $this->authHeader);
		$this->seeInDatabase('site_status', ['online' => 1]);
		$this->seeJsonStructure([
			'status' => [
				'created_at',
				'updated_at',
				'online'
			]
		]);
	}
}