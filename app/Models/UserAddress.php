<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/13/15
 * Time: 7:43 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAddress
 *
 * @property integer $id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property integer $zipcode
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $user_id
 */
class UserAddress extends Model
{
	protected $table = "user_addresses";

	protected $guarded = [];

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function order() {
		return $this->hasMany('App\Models\Order');
	}
}