<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/15/15
 * Time: 2:24 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	public function product() {
		return $this->belongsTo('App\Models\Product');
	}

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function address() {
		return $this->belongsTo('App\Models\UserAddress', 'user_address_id');
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