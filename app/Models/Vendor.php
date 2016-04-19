<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:47 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vendor
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $phone_number
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\VendorSetting $settings
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 */
class Vendor extends Model
{
	protected $hidden = [''];

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function products() {
		return $this->belongsToMany('App\Models\Product', 'vendor_product')->withPivot(['vendor_price', 'sale_price'])->withTimestamps();
	}

	public function settings() {
		return $this->hasOne('App\Models\VendorSetting');
	}

	public function orders() {
		return $this->hasMany('App\Models\Order');
	}
}