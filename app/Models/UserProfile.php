<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 4:57 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserProfile
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $date_of_birth
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile whereDateOfBirth($value)
 */
class UserProfile extends Model
{
	protected $fillable = ['first_name', 'last_name', 'phone_number', 'date_of_birth'];

	public function fullName() {
		return "{$this->first_name} {$this->last_name}";
	}
}