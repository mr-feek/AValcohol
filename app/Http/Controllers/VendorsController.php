<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 10:08 PM
 */

namespace App\Http\Controllers;

use App\Http\Filters\VendorsFilters;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
	public function get(Request $request) {
		$filters = new VendorsFilters($request->input());
		$vendors = Vendor::filter($filters)->with('deliveryZone')->get(); // filters is basically noop right now in vendorsfilters class
		$count = Vendor::filter($filters)->count();

		$this->authorize('get', $vendors); // todo: remove once admin middleware is setup

		return response()->json([
			'vendors' => $vendors,
			'total_count' => $count
		]);
	}
}