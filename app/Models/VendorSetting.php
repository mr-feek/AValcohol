<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:13 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VendorSetting
 *
 * @property integer $vendor_id
 * @property boolean $auto_accept_orders
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorSetting whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorSetting whereAutoAcceptOrders($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VendorSetting extends Model
{

}