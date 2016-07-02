<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 11:41 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginAsVendor extends Model
{
	protected $table = 'admin_login_as_vendor';
	protected $fillable = ['user_id', 'vendor_id'];
}