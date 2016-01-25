<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/15/15
 * Time: 2:24 AM
 */

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property integer $id
 * @property float $amount
 * @property string $status
 * @property integer $user_id
 * @property integer $user_address_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $note
 */
class Order extends Model
{
	public function products() {
		return $this->belongsToMany('App\Models\Entities\Product')->withPivot('product_price');
	}

	public function user() {
		return $this->belongsTo('App\Models\Entities\User');
	}

	public function address() {
		return $this->belongsTo('App\Models\Entities\UserAddress', 'user_address_id');
	}

	/**
	 * gets possible enum values for the status column
	 *
	 * TO DO: pull this directly from the DB values and make it universal on all models
	 * @return array
	 */
	public static function getStatusKeys() {
		return ['pending', 'out-for-delivery', 'delivered', 'pending-refund', 'refunded'];
	}
}