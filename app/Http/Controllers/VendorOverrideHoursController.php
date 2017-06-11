<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/10/16
 * Time: 3:55 PM
 */

namespace App\Http\Controllers;

use App\Models\OverrideVendorStoreHours;
use Illuminate\Http\Request;

class VendorOverrideHoursController extends Controller
{
	public function create(Request $request) {
		$this->validate($request, [
			'vendor_id' => 'required',
			'day_of_week' => 'required|int', // 0 - 6
			'override_start_date' => 'required|date_format:"Y-m-d"', // correct format?
			'override_end_date' => 'required|date_format:"Y-m-d"'
		]);

		$data = $request->input();

		$model = OverrideVendorStoreHours::create($data);

		return response()->json(['override_vendor_store_hours' => $model]);
	}

	public function delete(Request $request, int $vendorId, int $modelId) {
		$deleted = OverrideVendorStoreHours::find($modelId)->delete();
		return response()->json(['deleted' => $deleted]);
	}
}