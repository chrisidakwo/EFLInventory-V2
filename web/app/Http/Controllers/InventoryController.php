<?php

namespace App\Http\Controllers;

use App\Helpers\SalesHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Response;

class InventoryController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    //	/**
    //	 * @return Factory|View
    //	 */
    //	public function valuation() {
    //		//TODO: What and what should be valuated?
    //		// - value of inventory (show previous months of the year to current month)
    //		// - total number of products in inventory
    //		// - number of products in inventory
    //		// - table of low/out-of-stock products (showing quantity as a subtitle)
    //		// - table of expired products (showing quantity as a subtitle)
    //		// - table of damaged products (showing quantity as a subtitle)
    //		//TODO: How should they be evaluated?
    //		//TODO: How should the valuation be displayed? Ans: Pie chart? Bar chart? Graph? As figures?
//
    //		// Current value of inventory
    ////		$inventory_value = InventoryHelper::total();
    ////		$previous_months = [];
//
//
    //		// Get total value of products in stock
    //		$stocked_products = (new POSProduct())->all();
//
    //		$total_price = array_reduce($stocked_products, static function ($sum, $product) {
    //			return $sum + ($product->retail_price * $product->available_stock);
    //		}, 0);
//
    //		// foreach ($stocked_products as $product) {
    //		//     $total_price += ($product->retail_price * $product->available_stock);
    //		// }
//
    //		return view('inventory.valuation', compact('total_price'));
    //	}

    /**
     * @return JsonResponse
     */
    public function summary(): JsonResponse {
        $month_sales = SalesHelper::getMonthSales();
        $last_month_sales = SalesHelper::getLastMonthSales();
        $month_chart = SalesHelper::getSalesRange(today()->toDateString());
        $last_month_chart = SalesHelper::getSalesRange(Carbon::parse((today()->month - 1) . '/01/' . today()->year));

        return Response::json([
            'month_sales' => $month_sales,
            'last_month_sales' => $last_month_sales,
            'month_chart' => $month_chart,
            'last_month_chart' => $last_month_chart,
        ]);
    }
}
