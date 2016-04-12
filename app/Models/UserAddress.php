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
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $order
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAddress whereUserId($value)
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
	protected $table = 'user_addresses';

	protected $fillable = ['street', 'city', 'state', 'zipcode'];

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function order() {
		return $this->hasMany('App\Models\Order');
	}
}