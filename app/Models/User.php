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

class User extends Model
{
	public function addresses() {
		return $this->hasMany('App\Models\UserAddress');
	}
}