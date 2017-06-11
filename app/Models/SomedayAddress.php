<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/5/16
 * Time: 12:43 AM
 */

namespace App\Models;


use App\Models\DeliveryZone\Point;
use DB;
use Illuminate\Database\Eloquent\Model;

class SomedayAddress extends Model
{
	protected $fillable = ['location'];

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

	/**
	 * converts the location parameter into the mysql geom point string needed to be saved
	 * @param $location array containing lat and lng
	 */
	static function convertLocationArrayAttributeToMySQLPoint(&$location) {
		$locationAsPointModel = new Point($location['latitude'], $location['longitude']);
		$locationAsMySQLPoint = DB::raw("GEOMFROMTEXT('POINT($locationAsPointModel->latitude $locationAsPointModel->longitude)')");
		$location = $locationAsMySQLPoint;
	}
}
