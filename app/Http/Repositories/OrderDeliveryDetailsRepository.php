<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/22/16
 * Time: 1:35 AM
 */

namespace App\Http\Repositories;

use App\Http\Domain\OrderDeliveryDetails\PhotoManager;
use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;
use App\Models\OrderDeliveryDetail;

class OrderDeliveryDetailsRepository extends BaseRepository implements OrderDeliveryDetailsInterface
{

	/**
	 * @var PhotoSaver
	 */
	private $photoManager;

	public function __construct(OrderDeliveryDetail $model)
	{
		$this->model = $model;
		$this->photoManager = new PhotoManager();
	}

	/**
	 * saves the base64 encoded photo to the filesystem and saves the entity containing the encoded signature to the database
	 * @param array $data
	 * @return OrderDeliveryDetail
	 */
	public function create(array $data)
	{
		$this->model = new OrderDeliveryDetail($data);
		$this->model->photo_path = $this->photoManager->put($data['photoData']);
		$this->model->save();
		return $this->model;
	}
}