<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 10:51 AM
 */
class SiteStatusControllerTest extends TestCase
{
	public function testTurnStoreOffline() {
		$this->post('site/status', ['online' => 0]);
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
		$this->post('site/status', ['online' => 1]);
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