<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/2/16
 * Time: 4:06 AM
 */

namespace App\Http\Filters;

use App\Http\Traits\PaginationFilters;

class AdminOrderFilters extends QueryFilters
{
	use PaginationFilters;
	
	/**
	 * filters based on orders ready to be picked up by drivers
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function ready() {
		return $this->builder->whereHas('status', function($query) {
			$query->where([
				['vendor_status', 'accepted'],
				['delivery_status', 'pending'],
				['charge_authorized', true],
				['charge_captured', true]
			]);
		});
	}

	/**
	 * filters based on orders that are currently out for delivery
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function out() {
		return $this->builder->whereHas('status', function($query) {
			$query->where([
				['vendor_status', 'accepted'],
				['delivery_status', 'out-for-delivery'],
				['charge_authorized', true],
				['charge_captured', true]
			]);
		});
	}
}