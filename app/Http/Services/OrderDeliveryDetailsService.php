<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;
use App\Http\Repositories\Interfaces\OrderStatusInterface;

class OrderDeliveryDetailsService extends BaseService
{
	protected $repo;

	/**
	 * @var OrderStatusService
	 */
	private $orderStatusService;

	public function __construct(OrderDeliveryDetailsInterface $repo, OrderStatusService $orderStatusService)
	{
		$this->repo = $repo;
		$this->orderStatusService = $orderStatusService;
	}

    /**
     * Records an order identity entry
     * @param $data
     * @return mixed
     */
    public function create(array $data)
    {
		$deliveryDetails = $this->repo->create($data);

	    $orderStatusData = [
		    'delivery_status' => 'delivered',
		    'order_id' => $data['order_id']
	    ];

	    $this->orderStatusService->update($orderStatusData);
		return $deliveryDetails;
    }
}
