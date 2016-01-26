<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:57 PM
 */

namespace App\Models\Services;

use App\Models\Repositories\Interfaces\ProductInterface;

class ProductService extends BaseService
{
	public function __construct(ProductInterface $productRepo)
	{
		$this->repo = $productRepo;
	}

	public function getAll($data) {
		$collection = [];
		foreach ($data as $productData) {
			$collection[] = $this->repo->getById($productData['id']);
		}
		return $collection;
	}
}