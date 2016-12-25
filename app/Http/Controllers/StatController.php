<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/13/16
 * Time: 2:33 AM
 */

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatController extends Controller
{
	/**
	 * not ttested or anything, not efficient. yolo
	 * @param Request $request
	 * @return mixed
	 */
	public function getStats(Request $request) {
		// todo: optimize obviously but quick and dirty right now
		$totalSales = Order::count();
		$averageSaleAmount = round(Order::avg('full_charge_amount'), 2);
		$totalAggregated = Order::sum('full_charge_amount');
		$salesToday = Order::whereBetween('created_at', [Carbon::today(), Carbon::now()])->count();

		return response()->json([
			'total_sales' => $totalSales,
			'average_sale_amount' => $averageSaleAmount,
			'total_aggregated_sales_amount' => $totalAggregated,
			'sales_today' => $salesToday
		]);
	}
}
