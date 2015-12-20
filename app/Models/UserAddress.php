<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/13/15
 * Time: 7:43 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
	protected $table = "user_addresses";

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function order() {
		return $this->hasMany('App\Models\Order');
	}
}