<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/13/15
 * Time: 7:43 PM
 */

namespace App\Models\Entities;

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
 * @property-read \App\Models\Entities\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\Order[] $order
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserAddress whereUserId($value)
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
	protected $table = 'user_addresses';

	protected $fillable = ['street', 'city', 'state', 'zipcode'];

	public function user() {
		return $this->belongsTo('App\Models\Entities\User');
	}

	public function order() {
		return $this->hasMany('App\Models\Entities\Order');
	}
}