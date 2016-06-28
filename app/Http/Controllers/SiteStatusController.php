<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/28/16
 * Time: 10:02 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteStatusController extends Controller
{

	public function get(Request $request) {
		return response()->json([
			'status' => 'offline'
		]);
	}
}