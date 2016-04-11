<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:47 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Entities\Vendor
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $phone_number
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Entities\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\Product[] $products
 * @property-read \App\Models\Entities\VendorSetting $settings
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\Vendor whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\Order[] $orders
 */
class Vendor extends Model
{
	protected $hidden = [''];

	public function user() {
		return $this->belongsTo('App\Models\Entities\User');
	}

	public function products() {
		return $this->belongsToMany('App\Models\Entities\Product', 'vendor_product')->withPivot(['vendor_price', 'sale_price'])->withTimestamps();
	}

	public function settings() {
		return $this->hasOne('App\Models\Entities\VendorSetting');
	}

	public function orders() {
		return $this->hasMany('App\Models\Entities\Order');
	}
}