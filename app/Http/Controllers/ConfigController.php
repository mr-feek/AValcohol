<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/16/16
 * Time: 7:56 PM
 */

namespace App\Http\Controllers;


use App\Http\Traits\DeliveryHoursTrait;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
	use DeliveryHoursTrait;

	/**
	 * expose info to front end
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getConfig(Request $request) {
		if ($this->isOpenNow()) {
			$blastMessage = 'Ready to crack some brews? Order now to receive within the hour!';
		} else {
			$blastMessage = 'We are not currently accepting new orders. Our current hours are 6PM to 2AM';
		}

		return response()->json([
			'blastMessage' => $blastMessage
		]);
	}
}