<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/8/15
 * Time: 3:04 PM
 */

namespace App\Models;

use App\Models\UserAddress;

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
 */
class User extends Model
{

	use Billable;

	protected $hidden = ['password'];

	// black list for mass assignable
	protected $fillable = ['email', 'password', 'first_name', 'last_name', 'phone_number'];

	public function addresses() {
		return $this->hasMany('App\Models\UserAddress');
	}
}