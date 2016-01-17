<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/17/16
 * Time: 2:34 PM
 */

namespace App\Models;


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
	 * @var array possible reasons for address to be blacklisted
	 */
	public $reasons = array(
		'1' => 'fraternity'
	);
}