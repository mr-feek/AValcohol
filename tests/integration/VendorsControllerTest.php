<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 8/4/16
 * Time: 5:05 PM
 */
class VendorsControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();
		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testGetAllVendors() {
		$this->get('/vendors/', $this->authHeader);
		$this->seeJsonStructure([
			'vendors' => [
				'*' => [
					'id',
					'address',
					'name',
					'phone_number',
					'store_status',
					'created_at',
					'updated_at',
					'user_id',
					'delivery_zone' => [
						'id',
						'name',
						'points',
						'created_at',
						'updated_at'
					]
				]
			]
		]);
	}
}