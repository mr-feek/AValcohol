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
use Illuminate\Database\Eloquent\Model;

trait Filterable
{
	/**
	 * Filter a result set.
	 *
	 * @param  Builder $query
	 * @param Model $model
	 * @param  QueryFilters $filters
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, QueryFilters $filters)
	{
		return $filters->apply($this, $query);
	}
}