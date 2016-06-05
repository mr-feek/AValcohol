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
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

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
 * @property-read \App\Models\UserAddress $address
 */
class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
	use Billable;

	protected $hidden = ['password'];

	// white list for mass assignable
	protected $fillable = ['email', 'password'];

	public function address() {
		return $this->hasOne('App\Models\UserAddress');
	}

	public function profile() {
		return $this->hasOne('App\Models\UserProfile');
	}

	public function vendor() {
		return $this->hasOne('App\Models\Vendor');
	}

	public function isAdmin() {
		// todo
		if ($this->id === 1) {
			return true;
		}
	}

	public function isVendor() {
		if(is_null($this->vendor)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

	/**
	 * Get the name of the unique identifier for the user.
	 *
	 * @return string
	 */
	public function getAuthIdentifierName()
	{
		// TODO: Implement getAuthIdentifierName() method.
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		// TODO: Implement getAuthIdentifier() method.
	}

	/**
	 * Get the (hashed) password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		// TODO: Implement getRememberToken() method.
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		// TODO: Implement setRememberToken() method.
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		// TODO: Implement getRememberTokenName() method.
	}

	/**
	 * Determine if the entity has a given ability.
	 *
	 * @param  string $ability
	 * @param  array|mixed $arguments
	 * @return bool
	 */
	public function can($ability, $arguments = [])
	{
		// TODO: Implement can() method.
	}
}