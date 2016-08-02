<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/16/16
 * Time: 7:56 PM
 */

namespace App\Http\Controllers;

use App\Http\Services\SiteStatusService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
	/**
	 * expose info to front end
	 * @param Request $request
	 * @param SiteStatusService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getConfig(Request $request, SiteStatusService $service) {

		$blastMessage = 'Ready to crack some brews? Order now to receive within the hour!';
		$isClosed = !$service->isOpenNow($request->input());

		if ($isClosed) {
			$blastMessage = $service->reasonForStoreClosure();
		}

		return response()->json([
			'isClosed' => $isClosed,
			'blastMessage' => $blastMessage
		]);
	}
}