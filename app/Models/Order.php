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
}