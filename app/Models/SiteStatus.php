<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 7:43 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStatus extends Model
{
	protected $table = 'site_status';
	public $incrementing = false;
	protected $hidden = ['id'];
}