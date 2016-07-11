<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/7/16
 * Time: 12:58 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\OverrideVendorStoreHours
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $day_of_week
 * @property string $override_start_date
 * @property string $override_end_date
 * @property string $alternate_open_time
 * @property string $alternate_close_time
 * @property boolean $closed
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereDayOfWeek($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereOverrideStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereOverrideEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereAlternateOpenTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereAlternateCloseTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereClosed($value)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OverrideVendorStoreHours whereDeletedAt($value)
 */
class OverrideVendorStoreHours extends Model
{
	use SoftDeletes;
	
	protected $table = 'vendor_hours_overrides';
	protected $guarded = [];
}