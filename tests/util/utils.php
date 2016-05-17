<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/16/16
 * Time: 11:31 AM
 */

use App\Models\User;

class Utils extends TestCase {
	protected $vendorToken;
	protected $rawVendorData;

	public function __construct() {
		parent::setUp();
	}

	private function createVendor() {
		$data = [
			'email' => 'aaaabbcadsfsdf@asd.com',
			'password' => 'passswrodsss1234@#21sdl',
			'name' => 'first last',
			'address' => '123 candy cane lane',
			'phone_number' => '1231231234',
			'delivery_zone_id' => '1'
		];

		$this->post('/admin/vendor', $data);
		$responseData = json_decode($this->response->getContent());
		$this->assertNotNull($responseData->vendor->id, 'unable to create a test vendor to generate a token for');
		$this->rawVendorData = $data;
	}

	public function getVendorToken() {
		if ($this->vendorToken) {
			return $this->vendorToken;
		}

		$this->createVendor();
		$this->post('auth/login', $this->rawVendorData);
		$response = json_decode($this->response->getContent());
		$this->assertNotNull($response->token, 'Could not test protected routes because the test vendor does not exist');
		$this->vendorToken = $response->token;

		return $this->vendorToken;
	}

	public function generateTokenForUser(User $user) {
		return \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
	}

	public function cleanUp() {
		$user = User::where(['email' => $this->rawVendorData['email']])->first()->delete();
	}

}