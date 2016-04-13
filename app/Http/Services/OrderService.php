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
use Illuminate\Support\Facades\Event;

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
	 * Creates a new order and creates an authorization charge on the card
	 * @param $data
	 * @return mixed
	 */
	public function create($data) {
		$user = $this->userService->getUser($data['user']['id']);
		$address = $this->addressService->get($data['address']['id']);

		$products = [];
		foreach($data['products'] as $product) {
			$product = $this->vendorService->getProduct($product['pivot']['vendor_id'], $product['id']);
			$products[] = $product;
		}

		$order = $this->repo->createOrder($user, $address, $products, $data);
		$authorized = $this->repo->authorizeChargeOnCard($order, $data['stripe_token']);

		if (!$authorized) {
			// to do
		}

		return $order;
	}
}