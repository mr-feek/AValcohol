<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;

class OrderDeliveryDetailsService extends BaseService
{
	protected $repo;
	
	public function __construct(OrderDeliveryDetailsInterface $repo)
	{
		$this->repo = $repo;
	}

    /**
     * Records an order identity entry
     * @param $data
     * @return mixed
     */
    public function create(array $data)
    {
		$deliveryDetails = $this->repo->create($data);
		return $deliveryDetails;
    }
}
