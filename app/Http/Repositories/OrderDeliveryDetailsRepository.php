<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/22/16
 * Time: 1:35 AM
 */

namespace App\Http\Repositories;

use App\Http\Domain\OrderDeliveryDetails\Interfaces\PhotoManagerInterface;
use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;
use App\Models\OrderDeliveryDetail;
use Exception;

class OrderDeliveryDetailsRepository extends BaseRepository implements OrderDeliveryDetailsInterface
{

	/**
	 * @var PhotoSaver
	 */
	private $photoManager;

	public function __construct(OrderDeliveryDetail $model, PhotoManagerInterface $photoManager)
	{
		$this->model = $model;
		$this->photoManager = $photoManager;
	}

	/**
	 * saves the base64 encoded photo to the filesystem and saves the entity containing the encoded signature to the database
	 *
	 * @param array $data
	 *
	 * @return OrderDeliveryDetail
	 * @throws Exception
	 */
	public function create(array $data)
	{
		try {
			$this->model = new OrderDeliveryDetail($data);
			$this->model->photo_path = $this->photoManager->put($data['photoData']);;
			$this->model->save();
		} catch (Exception $e) {
			// cleanup saved photo from disk
			$this->photoManager->destroy($this->model);
			throw $e;
		}

		return $this->model;
	}

	public function get(int $id) {
		return $this->model->find($id);
	}
}
