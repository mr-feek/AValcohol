<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:17 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Entities\OrderStatus
 *
 * @property integer $order_id
 * @property string $vendor_status
 * @property string $delivery_status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderStatus whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderStatus whereVendorStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderStatus whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderStatus extends Model
{

}