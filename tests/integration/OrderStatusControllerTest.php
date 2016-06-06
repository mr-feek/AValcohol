<?php
use App\Models\Vendor;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/6/16
 * Time: 6:27 PM
 */
class OrderStatusControllerTest extends TestCase
{
	protected $vendor;

	public function setUp()
	{
		parent::setUp();
		$this->vendor = Vendor::find(1);
		$this->token = $this->utils->generateTokenForUser($this->vendor->user);
		$this->authHeader = ['Authorization' => 'Bearer ' . $this->token];
	}

	public function testVendorAcceptOrder() {
		$order = $this->fetchVendorPendingOrder();
		$data = [
			'vendor_status' => 'accepted'
		];

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'pending',
			'vendor_status' => 'accepted'
		]);

		// to do: verify charge captured
		// to do: verify email sent
	}

	public function testVendorRejectOrder() {
		$order = $this->fetchVendorPendingOrder();
		$data = [
			'vendor_status' => 'rejected'
		];

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'pending',
			'vendor_status' => 'rejected'
		]);

		// to do: verify charge deleted
		// to do: verify email sent
	}

	public function testDriverPickupOrder() {
		$order = $this->fetchDeliveryPendingOrder();
		$data = [
			'delivery_status' => 'out-for-delivery'
		];
		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'out-for-delivery',
			'vendor_status' => 'accepted'
		]);
	}

	public function testDriverDeliverOrder() {
		// i think this is handled in delivery details, check later
		$this->fail();
	}

	/**
	 * returns an order with vendor status === pending
	 * @return mixed
	 */
	private function fetchVendorPendingOrder() {
		$order = \App\Models\Order::where([
			'vendor_id' => $this->vendor->id,
		])->with(['status' => function($query) {
			$query->where('vendor_status', 'pending');
		}])->first();

		return $order;
	}

	private function fetchDeliveryPendingOrder() {
		$order = \App\Models\Order::whereHas('status', function($query) {
			$query->where('vendor_status', 'accepted');
			$query->where('delivery_status', 'pending');
		})->first();

		return $order;
	}
}