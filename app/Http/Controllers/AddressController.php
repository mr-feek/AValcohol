<?php

namespace App\Http\Controllers;

use App\Http\Traits\AddressTrait;
use App\Models\Entities\UserAddress;
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
			'user.id' => 'required'
		]);

		$address = $this->createAddress($request->input());

		return response()->json([
			'success' => true,
			'address' => $address
		]);
	}

	public function update(Request $request, $id) {
		$address = UserAddress::findOrFail($id);

		$this->enforceUserCanUpdateAddress($request['user_id'], $address);
		$address->update($request->input());

		return array(
			'success' => true,
			'address' => $address
		);
	}
}
