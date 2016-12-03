<?php
use App\Models\VendorStoreHours;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/7/16
 * Time: 1:08 PM
 */
class OverrideVendorHoursControllerTest extends TestCase
{
	//use \Laravel\Lumen\Testing\DatabaseTransactions;

	public function __construct()
	{
		parent::__construct();
		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testCreateOverrideVendorHours() {
		$vendor_id = 1;
		$dayOfWeek = VendorStoreHours::$days['monday'];

		$data = [
			'vendor_id' => $vendor_id,
			'day_of_week' => $dayOfWeek,
			'override_start_date' => '2016-01-03',
			'override_end_date' => '2016-01-05',
			'alternate_open_time' => '10:00',
			'alternate_close_time' => '15:00'
		];

		$this->post("vendor/{$vendor_id}/hours/override", $data, $this->authHeader);
		$this->seeInDatabase('vendor_hours_overrides', $data);
		$this->checkJson($data);
	}

	public function testDeleteOverrideVendorHours() {
		$model = \App\Models\OverrideVendorStoreHours::first();
		$vendorId = $model->vendor_id;
		$id = $model->id;

		$this->delete("vendor/{$vendorId}/hours/override/{$id}", [], $this->authHeader);

		$this->notSeeInDatabase('vendor_hours_overrides', [
			'id' => $id,
			'deleted_at' => NULL
		]);
	}

	private function checkJson(array $data)
	{
		$content = json_decode($this->response->getContent());
		$response = $content->override_vendor_store_hours;

		$expected = array_merge($data, [
			'id' => $response->id,
			'created_at' => $response->created_at,
			'updated_at' => $response->updated_at
		]);

		$this->seeJsonEquals(['override_vendor_store_hours' => $expected]);
	}
}