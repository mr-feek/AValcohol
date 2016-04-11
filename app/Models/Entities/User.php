<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/8/15
 * Time: 3:04 PM
 */

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property integer $phone_number
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $stripe_id
 * @property string $card_brand
 * @property string $card_last_four
 * @property boolean $mvp_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\UserAddress[] $addresses
 * @property-read \App\Models\Entities\UserProfile $profile
 * @property-read \App\Models\Entities\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereMvpUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereStripeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereCardBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\User whereCardLastFour($value)
 * @mixin \Eloquent
 */
class User extends Model
{

	use Billable;

	protected $hidden = ['password'];

	// white list for mass assignable
	protected $fillable = ['email', 'password'];

	public function addresses() {
		return $this->hasMany('App\Models\Entities\UserAddress');
	}

	public function profile() {
		return $this->hasOne('App\Models\Entities\UserProfile');
	}

	public function vendor() {
		return $this->hasOne('App\Models\Entities\Vendor');
	}
}