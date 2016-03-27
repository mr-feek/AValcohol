<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/8/15
 * Time: 3:04 PM
 */

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property integer $id
 * @property integer $upc
 * @property string $name
 * @property string $contains
 * @property string $ounces
 * @property string $container
 * @property boolean $featured
 * @property string $image_url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property float $sale_price
 */
class Product extends Model
{
	public function vendor() {
		return $this->belongsTo('App\Models\Entities\Vendor');
	}
}