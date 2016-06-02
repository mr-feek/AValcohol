<?php

namespace App\Http\Filters;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/2/16
 * Time: 4:02 AM
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilters
{
	/**
	 * @var key value array
	 */
	protected $filters;

	/**
	 * The builder instance.
	 *
	 * @var Builder
	 */
	protected $builder;

	public function __construct(array $filters)
	{
		$this->filters = $filters;
	}

	/**
	 * Apply the filters to the builder.
	 *
	 * @param  Builder $builder
	 * @return Builder
	 */
	public function apply(Builder $builder)
	{
		$this->builder = $builder;

		foreach ($this->filters as $name => $value) {
			if (! method_exists($this, $name)) {
				continue;
			}

			if (strlen($value)) {
				$this->$name($value);
			} else {
				$this->$name();
			}
		}

		return $this->builder;
	}
}