<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/25/16
 * Time: 4:57 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Entities\UserProfile
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $date_of_birth
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\UserProfile whereDateOfBirth($value)
 */
class UserProfile extends Model
{

}