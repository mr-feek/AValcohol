<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:47 PM
 */

namespace App\Models;

use App\Http\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

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
 * @property integer $delivery_zone_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor whereDeliveryZoneId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Vendor filter($filters)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VendorStoreHours[] $hours
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OverrideVendorStoreHours[] $overrideHours
 */
class Vendor extends Model
{
	use Filterable;
	use Eloquence;

	protected $hidden = [''];

	protected $fillable = ['name', 'address', 'phone_number', 'delivery_zone_id'];

	protected $appends = ['store_status'];

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

	public function hours() {
		return $this->hasMany('App\Models\VendorStoreHours');
	}

	public function overrideHours() {
		return $this->hasMany('App\Models\OverrideVendorStoreHours');
	}

	/*
	|--------------------------------------------------------------------------
	| Scopes
	|--------------------------------------------------------------------------
	|
	| scopey scopey scopey
	|
	*/

	/**
	 * for now this does not factor in any overrides that may be set!
	 *
	 * @param Builder $query
	 * @return Builder|static
	 */
	public function scopeIsOpen(Builder $query) {
		return $query->whereHas('hours', function($query) {
			$query->where([
				['day_of_week', 'DAYOFWEEK(NOW())'], // filter for today
				['open_time', '<', 'NOW()'], // opened before now
				['close_time', '>', 'NOW()'] // closes after now
			]);
		});
	}

	/*
	|--------------------------------------------------------------------------
	| accessors
	|--------------------------------------------------------------------------
	|
	| woo
	|
	*/
	public function getStoreStatusAttribute() {
		$open = $this->isStoreOpen();
		return $open ? 'open' : 'closed';
	}

	/*
	|--------------------------------------------------------------------------
	| Utils
	|--------------------------------------------------------------------------
	|
	|
	|
	*/

	/**
	 * @return bool whether or not this store is currently open
	 */
	private function isStoreOpen() {
		$res = self::isOpen()->whereId($this->id)->get();

		return $res->count() == 1;
	}
}