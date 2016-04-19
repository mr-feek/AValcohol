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
 * App\Models\OrderDenial
 *
 * @property integer $order_id
 * @property string $reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDenial whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDenial whereReason($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDenial whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDenial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDenial extends Model
{

}