<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/7/16
 * Time: 12:57 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VendorStoreHours
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $day_of_week
 * @property string $open_time
 * @property string $close_time
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereDayOfWeek($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereOpenTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereCloseTime($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorStoreHours whereDeletedAt($value)
 */
class VendorStoreHours extends Model
{
	protected $table = 'vendor_hours';

	public static $days = [
		'sunday' => 0,
		'monday' => 1,
		'tuesday' => 2,
		'wednesday' => 3,
		'thursday' => 4,
		'friday' => 5,
		'saturday' => 6,
	];

	protected $guarded = [];
}