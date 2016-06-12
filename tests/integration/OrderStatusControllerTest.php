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

		\Illuminate\Support\Facades\Mail::shouldReceive('queue')->once()->andReturnUsing(function($view, $viewParams) {
			$this->assertEquals('emails.order-accepted', $view['text']);
		});

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

		\Illuminate\Support\Facades\Mail::shouldReceive('queue')->once()->andReturnUsing(function($view, $viewParams) {
			$this->assertEquals('emails.order-not-accepted', $view['text']);
		});

		$this->patch("order/{$order->id}/status", $data, $this->authHeader);

		$this->seeJson(['success' => true]);

		$this->seeInDatabase('order_statuses', [
			'order_id' => $order->id,
			'delivery_status' => 'pending',
			'vendor_status' => 'rejected'
		]);

		// todo: verify charge deleted
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
		// this is handled in delivery details
		//$this->fail();
	}

	/**
	 * returns an order with vendor status === pending
	 * @return mixed
	 */
	private function fetchVendorPendingOrder() {
		$order = \App\Models\Order::where([
			'vendor_id' => $this->vendor->id,
		])->whereHas('status', function($query) {
			$query->where('vendor_status', 'pending');
			$query->where('delivery_status', 'pending');
		})->firstOrFail();

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