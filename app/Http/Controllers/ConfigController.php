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
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getConfig(Request $request, SiteStatusService $service) {
		$blastMessage = ''; // default is in front end right now.
		$isClosed = !$service->isOpenNow();
		if ($isClosed) {
			$blastMessage = $service->reasonForStoreClosure();
		}

		return response()->json([
			'isClosed' => $isClosed,
			'blastMessage' => $blastMessage
		]);
	}
}