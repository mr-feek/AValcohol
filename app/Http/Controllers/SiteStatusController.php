<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/28/16
 * Time: 10:02 PM
 */

namespace App\Http\Controllers;

use App\Models\SiteStatus;
use Illuminate\Http\Request;

class SiteStatusController extends Controller
{

	public function get(Request $request) {
		$model = SiteStatus::all()->first();
		return response()->json([
			'status' => $model
		]);
	}

	public function save(Request $request) {
		$model = SiteStatus::all()->first();
		$model->online = $request->input('online');
		$model->save();
		return response()->json([
			'status' => $model
		]);
	}
}