<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 7/2/16
 * Time: 10:15 PM
 */

namespace App\Http\Filters;

use App\Http\Traits\PaginationFilters;
use App\Http\Traits\SearchFilters;

class VendorsFilters extends QueryFilters
{
	use PaginationFilters;
	use SearchFilters;

}