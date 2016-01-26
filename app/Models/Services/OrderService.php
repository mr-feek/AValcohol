<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:35 PM
 */

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\OrderInterface;
use App\Events\OrderWasSubmitted;
use Illuminate\Support\Facades\Event;

class OrderService extends BaseService
{
	protected $userService;
	protected $addressService;
	protected $productService;

	public function __construct(OrderInterface $repo, UserService $userService, UserAddressService $addressService, ProductService $productService)
	{
		$this->repo = $repo;
		$this->userService = $userService;
		$this->addressService = $addressService;
		$this->productService = $productService;
	}

	public function create($data) {
		$user = $this->userService->getUser($data['user']['id']);
		$address = $this->addressService->get($data['address']['id']);
		$products = $this->productService->getAll($data['products']);
		$order = $this->repo->createOrderAndChargeUser($user, $address, $products, $data);

		// notify pusher etc
		Event::fire(new OrderWasSubmitted($order));

		return $order;
	}

}