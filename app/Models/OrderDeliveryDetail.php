<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 5:18 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderDeliveryDetail
 *
 * @property integer $order_id
 * @property string $picture_url
 * @property string $signature
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail wherePictureUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $photo_path
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail wherePhotoPath($value)
 */
class OrderDeliveryDetail extends Model
{
	protected $primaryKey = 'order_id';
	public $incrementing = false;
	protected $fillable = ['signature', 'order_id'];
	protected $hidden = ['photo_path'];
}