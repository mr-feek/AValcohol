<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/5/16
 * Time: 2:48 AM
 */

namespace App\Http\Traits;

trait SearchFilters
{
	public function q($data) {
		$searchData = explode(':', $data);
		// a specific field is being searched
		if (count($searchData) > 1) {
			return $this->builder->search($searchData[1], [$searchData[0] => 100]);
		}

		return $this->builder->search($data, array_values($this->model->searchableFields));
	}

	/*
	 * cannot support multi column explicit search right now i dont htink, checkout later if need be
	private function parseSearchData($data) {
		$terms = explode(' ', $data);
		$data = [];
		foreach ($terms as $term) {
			$exploded = explode(':', $term);
			$data[$exploded[0]] = $exploded[1];
		}

		return $data;
	}
	*/
}