<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 11/8/15
 * Time: 3:04 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property integer $id
 * @property integer $upc
 * @property string $name
 * @property string $contains
 * @property string $ounces
 * @property string $container
 * @property boolean $featured
 * @property string $image_url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property float $sale_price
 * @property-read \App\Models\Vendor $vendor
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUpc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereContains($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereOunces($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereContainer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereImageUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
	public function vendor() {
		return $this->belongsTo('App\Models\Vendor');
	}

	/**
	 * This overrides the default to array and flattens the pivot table attribute,
	 * merging the attributes so that they are not nested within a pivot attribute
	 *
	 * be careful that there arent any duplicaated attributes within the pivot table or they
	 * will overwrite!
	 * @param int $options
	 * @return array
	 *
	public function toArray($options = 0)
	{
		$attributes = $this->attributesToArray();
		$attributes = array_merge($attributes, $this->relationsToArray()['pivot']);
		return $attributes;
	}
	 */
}