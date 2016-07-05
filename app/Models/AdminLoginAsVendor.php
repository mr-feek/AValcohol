<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 11:41 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminLoginAsVendor
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $vendor_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminLoginAsVendor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminLoginAsVendor whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminLoginAsVendor whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminLoginAsVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminLoginAsVendor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminLoginAsVendor extends Model
{
	protected $table = 'admin_login_as_vendor';
	protected $fillable = ['user_id', 'vendor_id'];
}