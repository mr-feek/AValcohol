<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/16/16
 * Time: 3:47 PM
 */

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	protected $hidden = ['password'];

	public function user() {
		return $this->belongsTo('App\Models\Entities\User');
	}

	public function products() {
		return $this->belongsToMany('App\Models\Entities\Product', 'vendor_product')->withPivot(['vendor_price', 'sale_price'])->withTimestamps();
	}

	public function settings() {
		return $this->hasOne('App\Models\Entities\VendorSetting');
	}
}