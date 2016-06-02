<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/2/16
 * Time: 4:13 AM
 */

namespace App\Http\Traits;

use App\Http\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
	/**
	 * Filter a result set.
	 *
	 * @param  Builder      $query
	 * @param  QueryFilters $filters
	 * @return Builder
	 */
	public function scopeFilter($query, QueryFilters $filters)
	{
		return $filters->apply($query);
	}
}