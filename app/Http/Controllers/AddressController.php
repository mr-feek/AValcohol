<?php

namespace App\Http\Controllers;

use App\Http\Services\UserAddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	/**
	 * @param Request $request
	 * @param UserAddressService $service
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function create(Request $request, UserAddressService $service) {
		$this->validate($request, [
			'street' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zipcode' => 'required',
			'user.id' => 'required'
		]);

		$address = $service->create($request->input());

		return response()->json([
			'success' => true,
			'address' => $address
		]);
	}

	/**
	 * @param Request $request
	 * @param UserAddressService $service
	 * @param $id
	 * @return array
	 */
	public function update(Request $request, UserAddressService $service, $id) {
		$address = $service->update($request->input());

		return array(
			'success' => true,
			'address' => $address
		);
	}
}
