<?php

namespace App\Http\Controllers;

use App\Http\Traits\AddressTrait;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	use AddressTrait;

	/**
	 * This function will determine whether or not we can deliver to the address entered.
	 * For now, it just checks if the address is equal to 16801
	 * @param Request $request
	 * @return boolean
	 */
	public function validateAddress(Request $request) {
		$address = $request->input('address');
		$zip = $address['zip'];
		$canDeliver = $this->canDeliverToAddress($zip);

		return response()->json([
			'canDeliver' => $canDeliver,
			'message' => $this->cannot_deliver_message
		]);
	}
}
