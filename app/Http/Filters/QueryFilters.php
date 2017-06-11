<?php

namespace App\Http\Filters;

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/2/16
 * Time: 4:02 AM
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

	/**
	 * The model instance
	 * @var Model
	 */
	protected $model;

	public function __construct(array $filters)
	{
		$this->filters = $filters;
	}

	/**
	 * Apply the filters to the builder.
	 *
	 * @param Model $model
	 * @param  Builder $builder
	 * @return Builder
	 */
	public function apply(Model $model, Builder $builder)
	{
		$this->model = $model;
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