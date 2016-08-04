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
	//use \Laravel\Lumen\Testing\DatabaseTransactions; breaking update test for now because of dependency. should be deleted anyway in 3rd test

	public function __construct()
	{
		parent::__construct();
		$this->prepareRequestsWithAdminPrivileges();
	}

	public function testCreateVendorHours() {
		$vendor_id = 1;
		$dayOfWeek = VendorStoreHours::$days['monday'];

		$data = [
			'vendor_id' => $vendor_id,
			'day_of_week' => $dayOfWeek,
			'open_time' => '13:00',
			'close_time' => '15:00'
		];

		$this->post("vendor/{$vendor_id}/hours", $data, $this->authHeader);
		$this->seeInDatabase('vendor_hours', $data);
		$this->checkJson($data);

		return [
			'vendor_id' => $vendor_id,
			'store_hours_id' => json_decode($this->response->getContent())->vendor_store_hours->id
		];
	}

	/**
	 * @depends testCreateVendorHours
	 * @param array $data
	 * @internal param int $vendor_id
	 * @internal param int $store_hours_id
	 * @internal param int $vendor_id
	 */
	public function testUpdateVendorHours(array $data) {
		$dayOfWeek = VendorStoreHours::$days['monday'];
		$vendor_id = $data['vendor_id'];
		$storeHoursId = $data['store_hours_id'];

		$data = [
			'vendor_id' => $vendor_id,
			'day_of_week' => $dayOfWeek,
			'open_time' => '11:00',
			'close_time' => '17:00'
		];

		$this->put("vendor/{$vendor_id}/hours/{$dayOfWeek}/id/{$storeHoursId}", $data, $this->authHeader);

		$this->seeInDatabase('vendor_hours', $data);
		$data['deleted_at'] = null; // hack but whatever
		$this->checkJson($data);
	}

	/**
	 * @depends testCreateVendorHours
	 * @param array $data
	 * @internal param int $vendor_id
	 * @internal param int $store_hours_id
	 * @internal param int $vendor_id
	 */
	public function testDeleteVendorHours(array $data) {
		$dayOfWeek = VendorStoreHours::$days['monday'];
		$vendor_id = $data['vendor_id'];
		$storeHoursId = $data['store_hours_id'];

		$this->delete("vendor/{$vendor_id}/hours/{$storeHoursId}", [], $this->authHeader);

		$this->seeInDatabase('vendor_hours', ['id' => $storeHoursId]);
		$this->notSeeInDatabase('vendor_hours', ['id' => $storeHoursId, 'deleted_at' => null ]); // since we are using soft deletes
	}

	public function testGetVendorStoreHoursForTheWeek() {
		$vendorId = 1;
		$this->get("vendor/{$vendorId}/hours", $this->authHeader);

		$this->seeJsonStructure([
			'vendor_store_hours' => [
				'*' => [
					'vendor_id',
					'day_of_week',
					'open_time',
					'close_time'
				]
			]
		]);
	}

	private function checkJson(array $data)
	{
		$content = json_decode($this->response->getContent());
		$response = $content->vendor_store_hours;

		$expected = array_merge($data, [
			'id' => $response->id,
			'created_at' => $response->created_at,
			'updated_at' => $response->updated_at
		]);

		$this->seeJsonEquals(['vendor_store_hours' => $expected]);
	}
}