<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;
use Exception;
use App\Http\Services\OrderDeliveryDetails\Io\Writer;
use App\Http\Services\OrderDeliveryDetails\Domain\OrderIdentity;
use App\Http\Services\OrderDeliveryDetails\Domain\Image;
use App\Http\Services\OrderDeliveryDetails\Domain\SvgData;

class OrderIdentityService extends BaseService
{
    const DIRECTORY = 'temp_images/';
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
        $image = $this->repo->getImage(new Image($data['photo']));
        $orderIdentity = new OrderIdentity((int)$data['order_id'], $image, $signature);
        $writer = new Writer(self::DIRECTORY, $this->factory->getDbWriter(), $this->factory->getStreamWriter());
        return $writer->save($orderIdentity);
    }
}
