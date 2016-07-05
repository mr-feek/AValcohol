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
 * @property string $signature
 * @property string $photo_path when being saved
 * @property photo_data when being retrieved
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail wherePictureUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\OrderDeliveryDetail wherePhotoPath($value)
 * @property-read \App\Models\Order $order
 */
class OrderDeliveryDetail extends Model
{
	protected $primaryKey = 'order_id';
	public $incrementing = false;
	protected $fillable = ['signature', 'order_id'];
	protected $hidden = ['photo_path'];

	public function order() {
		return $this->hasOne('App\Models\Order');
	}

	/**
	 * automatically fetches the base64 encoded photo data from storage when this resource is requested and hydrates into 'photo_data'
	 * @param array $attributes
	 * @param bool $sync
	 * @return $this|void
	 */
	public function setRawAttributes(array $attributes, $sync = false)
	{
		parent::setRawAttributes($attributes, $sync);

		if (array_key_exists('photo_path', $attributes)) {
			$photoManager = app()->make('App\Http\Domain\OrderDeliveryDetails\Interfaces\PhotoManagerInterface');
			$this->photo_data = $photoManager->get($this);
		}

		return $this;
	}
}