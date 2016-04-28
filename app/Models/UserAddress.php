<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/13/15
 * Time: 7:43 PM
 */

namespace App\Models;

use App\Models\DeliveryZone\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\UserAddress
 *
 * @property integer $id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property integer $zipcode
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $user_id
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $order
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereUserId($value)
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
	protected $table = 'user_addresses';

	protected $fillable = ['street', 'city', 'state', 'zipcode', 'location'];

	public static function boot()
	{
		parent::boot();
		//Register save function
		$conversion = function($address) {
			//Convert points to location value
			if($address->isDirty('location')) {
				self::convertLocationArrayAttributeToMySQLPoint($address->attributes['location']);
			}
		};
		parent::saving($conversion);
	}

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function order() {
		return $this->hasMany('App\Models\Order');
	}

	/**
	 * converts the location parameter into the mysql geom point string needed to be saved
	 * @param $location array containing lat and lng
	 */
	static function convertLocationArrayAttributeToMySQLPoint(&$location) {
		$locationAsPointModel = new Point($location['latitude'], $location['longitude']);
		// THIS SHOULD BE SANITIZED. use DB::connection->getPDO()->quote() ? floatval() ?
		$locationAsMySQLPoint = DB::raw("GEOMFROMTEXT('POINT($locationAsPointModel->latitude $locationAsPointModel->longitude)')");
		$location = $locationAsMySQLPoint;
	}

	//Overridden to properly hydrate location attribute into points model when retrieved from db
	public function setRawAttributes(array $attributes, $sync = false)
	{
		if (array_key_exists('location', $attributes)) {
			$attributes['location'] = self::convertMySQLPointAttributeToPointModel($attributes['location']);
		}
		parent::setRawAttributes($attributes, $sync);
	}

	/**
	 * turns MySQL point syntax into point model
	 * @param $location
	 * @return Point
	 */
	static function convertMySQLPointAttributeToPointModel(&$location) {
		if (substr($location, 0, 20) === 'GEOMFROMTEXT(\'POINT(') {
			$pointString = substr($location, 20, -3); // capture values after POINT( and before closing )')
			$coord = explode(' ', $pointString);
			$point = new Point((double) $coord[0], (double) $coord[1]);
			$location = $point;
		}
	}
}