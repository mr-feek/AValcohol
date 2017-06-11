<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 2:35 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderInterface;
use App\Events\OrderWasSubmitted;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Stripe\Error\Card;

class OrderService extends BaseService
{
	protected $userService;
	protected $addressService;
	protected $productService;
	protected $vendorService;

	public function __construct(OrderInterface $repo, UserService $userService, UserAddressService $addressService, ProductService $productService,
								VendorService $vendorService)
	{
		$this->repo = $repo;
		$this->userService = $userService;
		$this->addressService = $addressService;
		$this->productService = $productService;
		$this->vendorService = $vendorService;
	}

	/**
	 * @param $id
	 * @return Order
	 */
	public function getByOrderId($id) {
		return $this->repo->getByOrderId($id);
	}

	/**
	 * Creates a new order and creates an authorization charge on the card
	 * @param $data
	 * @return mixed
	 */
	public function create(array $data) {
		$user = $this->userService->getUser($data['user']['id']);
		$address = $this->addressService->get($data['address']['id']);

		$products = [];
		foreach ($data['products'] as $p) {
			$product = $this->vendorService->getProduct($p['pivot']['vendor_id'], $p['id']);
			for ($i = 0; $i < $p['quantity']; $i++) {
				$products[] = $product;
			}
		}

		$order = new \stdClass();
		DB::transaction(function() use ($user, $address, $products, $data, &$order) {
			$order = $this->repo->createOrder($user, $address, $products, $data);
			$this->repo->authorizeChargeOnCard($order, $data['stripe_token']); // will throw an exception if fails
		});

		return $order;
	}

	public function capturePreExistingCharge(Order $order) {
		return $this->repo->capturePreExistingCharge($order);
	}

	public function deletePreExistingCharge(Order $order) {
		return $this->repo->deletePreExistingCharge($order);
	}
}
