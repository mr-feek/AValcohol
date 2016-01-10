<?php

namespace App\Http\Controllers;

use App\Http\Traits\AddressTrait;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	use AddressTrait;

	public function create(Request $request) {
		$this->validate($request, [
			'street' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zipcode' => 'required',
			'user_id' => 'required'
		]);

		$address = $this->createAddress($request->input());

		return response()->json([
			'success' => true,
			'address' => $address
		]);
	}

	/**
	 * This function will determine whether or not we can deliver to the address entered.
	 * For now, it just checks if the address is equal to 16801
	 * @param Request $request
	 * @return boolean
	 */
	public function validateAddress(Request $request) {
		$this->validate($request, [
			'address.zipcode' => 'required'
		]);

		$address = $request->input('address');
		$zip = $address['zipcode'];
		$canDeliver = $this->canDeliverToAddress($zip);

		return response()->json([
			'canDeliver' => $canDeliver,
			'message' => $this->cannot_deliver_message
		]);
	}
}
