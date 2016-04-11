<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:13 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Entities\VendorSetting
 *
 * @property integer $vendor_id
 * @property boolean $auto_accept_orders
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\VendorSetting whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\VendorSetting whereAutoAcceptOrders($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\VendorSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\VendorSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VendorSetting extends Model
{

}