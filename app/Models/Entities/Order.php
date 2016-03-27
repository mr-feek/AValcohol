<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/15/15
 * Time: 2:24 AM
 */

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property integer $id
 * @property float $amount
 * @property integer $user_id
 * @property integer $user_address_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $note
 */
class Order extends Model
{
	public function products() {
		return $this->belongsToMany('App\Models\Entities\Product')->withPivot(['product_vendor_price', 'product_sale_price']);
	}

	public function user() {
		return $this->belongsTo('App\Models\Entities\User');
	}

	public function address() {
		return $this->belongsTo('App\Models\Entities\UserAddress', 'user_address_id');
	}

	public function status() {
		return $this->hasOne('App\Models\Entities\OrderStatus');
	}

	// signature, picture, etc.
	public function deliveryDetails() {
		return $this->hasOne('App\Models\Entities\OrderDeliveryDetail');
	}
}