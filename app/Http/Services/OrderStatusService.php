<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 4/13/16
 * Time: 4:22 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderStatusInterface;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;

class OrderStatusService extends BaseService
{
	/**
	 * @var OrderService
	 */
	private $orderService;
	/**
	 * @var UserService
	 */
	private $userService;

	public function __construct(OrderStatusInterface $repo, UserService $userService, OrderService $orderService)
	{
		$this->repo = $repo;
		$this->orderService = $orderService;
		$this->userService = $userService;
	}

	/**
	 * @param array $data
	 * @return \App\Models\OrderStatus
	 */
	public function vendorRejectOrder(array $data) {
		$success = $this->repo->update($data);
		$order = $this->orderService->getByOrderId($data['order_id']);
		$user = $this->userService->getUser($order->user_id);

		// delete the pre existing authorized charge
		Stripe::setApiKey(getenv('STRIPE_SECRET'));
		$this->orderService->deletePreExistingCharge($order);

		app('queue.connection'); // Just to initialize binding
		Mail::queue(['text' => 'emails.order-not-accepted'], ['user' => $user, 'order' => $order], function($message) use ($user) {
			$message->to($user->email, $user->profile->fullName());
			$message->subject('Unfortunately Your Order Was Not Accepted :(');
			$message->from('no-reply@avalcohol.com', 'Aqua Vitae');
		});

		return $success;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function vendorAcceptOrder(array $data) {
		$success = $this->repo->update($data);
		$order = $this->orderService->getByOrderId($data['order_id']);
		$user = $this->userService->getUser($order->user_id);
		
		// capture the pre existing authorized charge
		Stripe::setApiKey(getenv('STRIPE_SECRET'));
		$this->orderService->capturePreExistingCharge($order);

		app('queue.connection'); // Just to initialize binding
		Mail::queue(['text' => 'emails.order-accepted'], ['user' => $user, 'order' => $order], function($message) use ($user) {
			$message->to($user->email, $user->profile->fullName());
			$message->subject('Your Order Has Been Accepted!');
			$message->from('no-reply@avalcohol.com', 'Aqua Vitae');
		});

		return $success;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function driverPickUpOrder(array $data) {
		$success = $this->repo->update($data);
		$order = $this->orderService->getByOrderId($data['order_id']);
		$user = $this->userService->getUser($order->user_id);
		
		app('queue.connection'); // Just to initialize binding
		Mail::queue(['text' => 'emails.out-for-delivery'], ['user' => $user, 'order' => $order], function($message) use ($user) {
			$message->to($user->email, $user->profile->fullName());
			$message->subject('Woohoo your order is on its way!');
			$message->from('no-reply@avalcohol.com', 'Aqua Vitae');
		});

		return $success;
	}

	/**
	 * @param array $data
	 */
	public function update(array $data) {
		$this->repo->update($data);
	}
}