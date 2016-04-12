<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/15/15
 * Time: 2:24 AM
 */

namespace App\Models;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\User $user
 * @property-read \App\Models\UserAddress $address
 * @property-read \App\Models\OrderStatus $status
 * @property-read \App\Models\OrderDeliveryDetail $deliveryDetails
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUserAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $vendor_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereVendorId($value)
 */
class Order extends Model
{
	public function products() {
		return $this->belongsToMany('App\Models\Product')->withPivot(['product_vendor_price', 'product_sale_price']);
	}

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function address() {
		return $this->belongsTo('App\Models\UserAddress', 'user_address_id');
	}

	public function status() {
		return $this->hasOne('App\Models\OrderStatus');
	}

	// signature, picture, etc.
	public function deliveryDetails() {
		return $this->hasOne('App\Models\OrderDeliveryDetail');
	}
}