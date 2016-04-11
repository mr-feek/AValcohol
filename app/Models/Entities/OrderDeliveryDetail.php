<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:18 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Entities\OrderDeliveryDetail
 *
 * @property integer $order_id
 * @property string $picture_url
 * @property string $signature
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderDeliveryDetail whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderDeliveryDetail wherePictureUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderDeliveryDetail whereSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderDeliveryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\OrderDeliveryDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDeliveryDetail extends Model
{

}