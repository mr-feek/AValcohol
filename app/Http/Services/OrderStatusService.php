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

class OrderStatusService extends BaseService
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var OrderRepository
	 */
	private $orderRepository;
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

		// to do: delete user's charge authorization

		Mail::setQueue(app('queue.connection'))->queue(['text' => 'emails.order-not-accepted'], ['user' => $user, 'order' => $order], function($message) use ($user) {
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
		return $this->repo->update($data);
		// to do: capture users charge authorization
		// to do: email customer with receipt and that vendor accepted order
	}

	public function driverPickUpOrder(array $data) {
		return $this->repo->update($data);
	}

	public function update(array $data) {
		$this->repo->update($data);
	}
}