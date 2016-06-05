<?php

use Illuminate\Support\Facades\Gate;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/17/16
 * Time: 8:52 PM
 */
class OrderPolicyTest extends TestCase
{

	public function testVendorGetOrderAccess() {
		$user = \App\Models\Vendor::find(1)->user;
		$badUser = \App\Models\Vendor::find(2)->user;
		$orders = $user->vendor->orders;

		foreach($orders as $order) {
			$res = Gate::forUser($user)->allows('get', $order);
			$this->assertTrue($res);

			$badRes = Gate::forUser($badUser)->allows('get', $order);
			$this->assertFalse($badRes);
		}
	}
}