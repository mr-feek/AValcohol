<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/26/16
 * Time: 1:56 PM
 */

namespace App\Http\Repositories;

use App\Models\Product;
use App\Http\Repositories\Interfaces\ProductInterface;

class ProductRepository extends BaseRepository implements ProductInterface
{
	public function __construct(Product $product)
	{
		$this->model = $product;
	}

	public function getById($id) {
		return $this->model = Product::findOrFail($id);
	}
}