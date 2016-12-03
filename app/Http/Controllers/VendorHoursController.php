<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/7/16
 * Time: 1:04 PM
 */

namespace App\Http\Controllers;

use App\Models\VendorStoreHours;
use Illuminate\Http\Request;

class VendorHoursController extends Controller
{
	public function create(Request $request) {
		$this->validate($request, [
			'vendor_id' => 'required',
			'day_of_week' => 'required|int', // 0 - 6
			'open_time' => 'required|date_format:"G:i"',
			'close_time' => 'required|date_format:"G:i"'
		]);

		$data = $request->input();

		$model = VendorStoreHours::create($data);

		return response()->json(['vendor_store_hours' => $model]);
	}

	/**
	 * @param Request $request
	 * @param $vendorId
	 * @param $dayOfWeek
	 * @return mixed
	 */
	public function update(Request $request, int $vendorId, int $dayOfWeek, int $storeHoursId) {
		$this->validate($request, [
			'vendor_id' => 'required',
			'day_of_week' => 'required|int', // 0 - 6
			'open_time' => 'required|date_format:"G:i"',
			'close_time' => 'required|date_format:"G:i"'
		]);

		$data = $request->input();

		$model = VendorStoreHours::findOrFail($storeHoursId);

		// find or fail must be primary key so this wont work
		//$model = VendorStoreHours::findOrFail([
			//'id' => $storeHoursId,
			//'vendor_id' => $data['vendor_id'],
			//'day_of_week' => $data['day_of_week']
		//]);

		$model->update($data);

		return response()->json(['vendor_store_hours' => $model]);
	}

	public function delete(Request $request, int $vendorId, int $storeHoursId) {
		$model = VendorStoreHours::find($storeHoursId);
		$success = $model->delete();

		return response()->json(['success' => $success]);
	}

	public function getWeeklyHours(Request $request, int $vendorId) {
		$hours = VendorStoreHours::where(['vendor_id' => $vendorId])->get();
		return response()->json(['vendor_store_hours' => $hours]);
	}
}