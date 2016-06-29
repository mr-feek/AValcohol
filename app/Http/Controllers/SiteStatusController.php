<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/28/16
 * Time: 10:02 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\SiteStatusService;
use App\Models\SiteStatus;
use Illuminate\Http\Request;

class SiteStatusController extends Controller
{

	/**
	 * @param Request $request
	 * @param SiteStatusService $service
	 * @return mixed
	 */
	public function get(Request $request, SiteStatusService $service) {
		return response()->json([
			'online' => $service->isOpenNow()
		]);
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function save(Request $request) {
		$model = SiteStatus::all()->first();
		$model->online = $request->input('online');
		$model->save();
		return response()->json([
			'status' => $model
		]);
	}
}