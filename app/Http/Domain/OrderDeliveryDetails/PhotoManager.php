<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/7/16
 * Time: 1:59 AM
 */

namespace App\Http\Domain\OrderDeliveryDetails;

use App\Exceptions\APIException;
use App\Http\Domain\OrderDeliveryDetails\Interfaces\PhotoManagerInterface;
use App\Models\OrderDeliveryDetail;
use Illuminate\Support\Facades\Storage;

class PhotoManager implements PhotoManagerInterface
{
	/**
	 * @var \Tymon\JWTAuth\Contracts\Providers\Storage
	 */
	private $disk;

	private $photoBaseDir;

	private $whitelistedExtensions = ['.jpg', '.jpeg', '.png'];

	public function __construct()
	{
		$this->photoBaseDir = 'customer_photos/';
		$this->disk = Storage::disk('s3');
		$env = app()->environment();

		if ($env === 'testing') {
			// still gonna hit up s3, just gonna change base directory so we can clean it out if need be
			$this->photoBaseDir = 'testing/customer_photos/';
		}

		if ($env === 'local' || (env('OFFLINE_MODE') === true)) {
			$this->disk = Storage::disk('local');
		}
	}

	/**
	 * Persists the supplied base64 encoded image data
	 * @param the|string $data the base64 encoded picture data IE data:image/png;base64,iVBORw0KGgoA...
	 * @return string the fully quantified path to the saved photo
	 * @throws APIException if picture could not be saved
	 *
	 * TODO: if an error happens while saving, cleanup this file
	 */
	public function put(string $data)
	{
		$decodedImageData = $this->decodeImageData($data);
		$fileName = $this->generateFilename();
		$location = $this->generateFilePath();
		$fullFilePath = $location . $fileName . $decodedImageData['extension'];

		$result = $this->disk->put($fullFilePath, $decodedImageData['data']);

		if ($result === false) {
			// freak out, this should never happen
			throw new APIException('could not persist the photo taken of this customer.');
		}

		return $fullFilePath;
	}

	/**
	 * parses the provided base 64 data
	 * @param string $data
	 * @return array with data and extension
	 */
	private function decodeImageData(string $data) {
		list($type, $data) = explode(';', $data);
		list(, $data) = explode(',', $data);
		$data = base64_decode($data);
		$extension = '.' . explode('/', $type)[1];

		$this->authorizeProvidedExtension($extension);

		return [
			'data' => $data,
			'extension' => $extension
		];
	}

	/**
	 * @param string $extension
	 * @throws APIException
	 */
	private function authorizeProvidedExtension(string $extension) {
		if (!in_array($extension, $this->whitelistedExtensions)) {
			// freak out.
			throw new APIException("cannot save file with extension {$extension}");
		}
	}

	/**
	 * @return string random filename
	 */
	private function generateFilename() {
		return md5(uniqid(rand(), true));
	}

	/**
	 * @return string path in format of directory/2016/05/01/ and ensures said directory is created
	 */
	private function generateFilePath() {
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$directory = $this->photoBaseDir . "{$year}/{$month}/{$day}/";

		return $directory;
	}

	/**
	 * retrieves the given photo from storage
	 * @param OrderDeliveryDetail $model
	 * @return string base64 of image
	 */
	public function get(OrderDeliveryDetail $model)
	{
		$imageData = $this->disk->get($model->photo_path);
		$type = pathinfo($model->photo_path, PATHINFO_EXTENSION);
		return $this->encodeImageData($imageData, $type);
	}

	/*
	 * uhhhh
	 * @param string $data
	 * @return string base64
	 */
	private function encodeImageData(string $data, $type) {
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
}