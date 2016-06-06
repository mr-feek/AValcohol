<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/22/16
 * Time: 1:35 AM
 */

namespace App\Http\Repositories;

use App\Exceptions\APIException;
use App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface;
use App\Models\OrderDeliveryDetail;

class OrderDeliveryDetailsRepository extends BaseRepository implements OrderDeliveryDetailsInterface
{
	private $photoBaseDir;
	private $whitelistedExtensions = ['.jpg', '.jpeg', '.png'];

	public function __construct(OrderDeliveryDetail $model)
	{
		$this->model = $model;
		$this->photoBaseDir = storage_path('photos/');
	}

	/**
	 * saves the base64 encoded photo to the filesystem and saves the entity containing the encoded signature to the database
	 * @param array $data
	 * @return OrderDeliveryDetail
	 */
	public function create(array $data)
	{
		$this->model = new OrderDeliveryDetail($data);
		$this->model->photo_path = $this->savePhoto($data['photoData']);
		$this->model->save();
		return $this->model;
	}

	/**
	 * @param $data the encoded picture data IE data:image/png;base64,iVBORw0KGgoA...
	 * @return string the fully quantified path to the saved photo
	 * @throws APIException if picture could not be saved
	 *
	 * TODO: if an error happens while saving, cleanup this file
	 */
	public function savePhoto($data)
	{
		list($type, $data) = explode(';', $data);
		list(, $data) = explode(',', $data);
		$data = base64_decode($data);
		$extension = '.' . explode('/', $type)[1];

		if (!in_array($extension, $this->whitelistedExtensions)) {
			// freak out.
			throw new APIException("cannot save file with extension {$extension}");
		}

		$fileName = $this->generateFilename();
		$location = $this->generateFilePath();
		$fullFilePath = $location . $fileName . $extension;

		$result = file_put_contents($fullFilePath, $data);

		if ($result === false) {
			// freak out, this should never happen
			throw new APIException('could not persist the photo taken of this customer.');
		}

		return $fullFilePath;
	}

	/**
	 * should this be a hash of order id and something instead?
	 * @return mixed
	 */
	private function generateFilename() {
		return uniqid('', true);
	}

	/**
	 * @return string path in format of directory/2016/05/01/ and ensures said directory is created
	 */
	private function generateFilePath() {
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$directory = $this->photoBaseDir . "{$year}/{$month}/{$day}/";

		if (!is_dir($directory)) {
			// dir doesn't exist, make it
			$old = umask(0);
			mkdir($directory, 0755, true); // TODO: fix permissions
			umask($old);
		}

		return $directory;
	}
}