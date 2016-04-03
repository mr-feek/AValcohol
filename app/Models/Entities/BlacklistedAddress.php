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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereReason($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entities\BlacklistedAddress whereUpdatedAt($value)
 * @mixin \Eloquent
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