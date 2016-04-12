<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/8/15
 * Time: 3:04 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $stripe_id
 * @property string $card_brand
 * @property string $card_last_four
 * @property boolean $mvp_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserAddress[] $addresses
 * @property-read \App\Models\UserProfile $profile
 * @property-read \App\Models\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereMvpUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStripeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCardBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCardLastFour($value)
 * @mixin \Eloquent
 */
class User extends Model
{

	use Billable;

	protected $hidden = ['password'];

	// white list for mass assignable
	protected $fillable = ['email', 'password'];

	public function addresses() {
		return $this->hasMany('App\Models\UserAddress');
	}

	public function profile() {
		return $this->hasOne('App\Models\UserProfile');
	}

	public function vendor() {
		return $this->hasOne('App\Models\Vendor');
	}
}