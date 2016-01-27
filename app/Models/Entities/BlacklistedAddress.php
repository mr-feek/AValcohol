<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/17/16
 * Time: 2:34 PM
 */

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BlacklistedAddress
 *
 * @property integer $id
 * @property integer $reason
 * @property string $street
 * @property string $city
 * @property string $state
 * @property integer $zipcode
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class BlacklistedAddress extends Model
{
	/**
	 * possible reasons for being blacklisted
	 * @return string
	 */
	public function getReason() {
		switch($this->reason) {
			case 1:
				return "We're sorry, but at this time we cannot deliver to fraternities";
		}
	}
}