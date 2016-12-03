<?php

namespace App\Http\Controllers;

use App\Exceptions\BlacklistedAddressException;
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
			'location.longitude' => 'required',
			'location.latitude' => 'required',
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

	public function getDeliveryZoneID(Request $request, UserAddressService $service) {
		$this->validate($request, [
			'longitude' => 'required',
			'latitude' => 'required',
			'street' => 'required'
		]);

		$data = [
			'location' => [
				'longitude' => $request->input('longitude'),
				'latitude' => $request->input('latitude')
			],
			'street' => $request->input('street')
		];

		try {
			$deliveryZoneId = $service->getDeliveryZoneID($data);
		} catch(BlacklistedAddressException $e) {
			return response()->json([
				'success' => false,
				'reason' => 'blacklisted',
				'message' => $e->getMessage()
			]);
		}

		if ($deliveryZoneId > 0) {
			return response()->json([
				'success' => true,
				'delivery_zone_id' => $deliveryZoneId
			]);
		}

		return response()->json([
			'success' => false,
			'message' => 'Address not in any delivery zone'
		]);
	}
}
