<?php
use App\Models\VendorStoreHours;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/7/16
 * Time: 1:08 PM
 */
class VendorHoursControllerTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function __construct()
	{
		parent::__construct();
		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testCreateVendorHours() {
		$id = 1;
		$dayOfWeek = VendorStoreHours::$days['monday'];

		$data = [
			'vendor_id' => $id,
			'day_of_week' => $dayOfWeek,
			'open_time' => '13:00',
			'close_time' => '15:00'
		];

		$this->post("vendor/{$id}/hours", $data, $this->authHeader);
		$this->seeInDatabase('vendor_hours', $data);
		$this->checkJson($data);
		return $id;
	}

	private function checkJson($data)
	{
		$response = json_decode($this->response->getContent())->vendor_store_hours;
		$expected = array_merge($data, [
			'id' => $response->id,
			'created_at' => $response->created_at,
			'updated_at' => $response->updated_at
		]);

		$this->seeJsonEquals(['vendor_store_hours' => $expected]);
	}
}