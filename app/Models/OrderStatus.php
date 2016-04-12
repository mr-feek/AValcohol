<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderStatus
 *
 * @property integer $order_id
 * @property string $vendor_status
 * @property string $delivery_status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereVendorStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $charge_id
 * @property boolean $charge_authorized
 * @property boolean $charge_captured
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereChargeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereChargeAuthorized($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderStatus whereChargeCaptured($value)
 */
class OrderStatus extends Model
{

}