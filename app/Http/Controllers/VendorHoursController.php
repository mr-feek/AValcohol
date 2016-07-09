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
}