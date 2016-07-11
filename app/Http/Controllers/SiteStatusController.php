<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/28/16
 * Time: 10:02 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\SiteStatusService;
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
	 * @return mixed whether or not the store is now online
	 */
	public function save(Request $request, SiteStatusService $service) {
		$this->validate($request, [
			'online' => 'required|bool'
		]);

		$service->setStatus($request->input());

		// lets return whether or not the store is online. An admin can set the store as online, but it will still not be
		// online if it is outside of delivery hours. essentially this power is just so that an admin can turn off the
		// store during "open hours" in case of emergency. An admin cannot turn on a store outside of "open hours"

		return response()->json([
			'online' => $service->isOpenNow(),
			'updatable' => $service->adminCanUpdate()
		]);
	}
}