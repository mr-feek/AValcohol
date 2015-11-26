<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
	/**
	 * This function will determine whether or not we can deliver to the address entered.
	 * For now, it just checks if the address is equal to 16801
	 * @param Request $request
	 * @return boolean
	 */
	public function validateAddress(Request $request) {
		$address = $request->input('address');
		$zip = $address['zip'];
		$canDeliver = true;

		if ($zip !== '16801') {
			$canDeliver = false;
		}

		return response()->json(['canDeliver' => $canDeliver ]);
	}
}
