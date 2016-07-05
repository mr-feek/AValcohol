<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 7:43 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SiteStatus
 *
 * @property integer $id
 * @property boolean $online
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteStatus whereOnline($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SiteStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SiteStatus extends Model
{
	protected $table = 'site_status';
	public $incrementing = false;
	protected $hidden = ['id'];
}