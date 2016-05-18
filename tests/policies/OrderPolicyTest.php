<?php

use Utils;
use Illuminate\Support\Facades\Gate;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/17/16
 * Time: 8:52 PM
 */
class OrderPolicyTest extends TestCase
{
	protected $utils;

	public function setUp()
	{
		parent::setUp();
		$this->utils = new Utils();
	}

	public function testVendorGetOrderAccess() {
		$user = \App\Models\User::find(1);
		$badUser = \App\Models\User::find(2);

		$orders = $user->vendor->orders;

		foreach($orders as $order) {
			$res = Gate::forUser($user)->allows('vendorGetOrder', $order);
			$this->assertTrue($res);

			$badRes = Gate::forUser($badUser)->allows('vendorGetOrder', $order);
			$this->assertFalse($badRes);
		}
	}
}