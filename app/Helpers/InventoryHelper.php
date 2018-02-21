<?php
/**
 * Class InventoryHelper.
 *
 * Description Here
 *
 * @author: Ekojoka Christolight Idakwo <chris.idakwo@gmail.com>
 * @date: 26/12/2017
 * @time: 08:39
 * @package: App\Helpers;
 */

namespace App\Helpers;


use App\Batch;
use App\POSProduct;
use App\ProductVariation;
use Carbon\Carbon;

class InventoryHelper {

    // TODO: Refactor methods. Reduce code reuse.

    /**
     ** total value of inventory
     *
     * @return float|int
     */
    public static function total() {
        return self::_getTotal();
    }

    /**
     ** total number of products in stock
     *
     * @return int|mixed
     */
    public static function totalItems() {
        return ProductVariation::all()->sum("stock");
    }

    /**
     ** get a list of products below their stock threshold
     *
     * @return array
     */
    public static function lowStockProducts() {
        $products = ProductVariation::all();
        $low_stock = [];

        foreach ($products as $product) {
            if($product->stock <= $product->stock_threshold) {
                $low_stock[] = $product;
            }
        }

        return $low_stock;
    }

    /**
     ** get a list of all expired products
     *
     * @return array
     */
    public static function expiredProducts() {
        $products = [];
        $batches = Batch::all()->where("on_sale", 1);
        $today = today("Africa/Lagos")->toDateString();

        foreach ($batches as $batch) {
            if(Carbon::parse($batch->expiry_date)->toDateString() <= $today) {
                $products[] = $batch;
            }
        }

        return $products;
    }


    #region Private functions
    private static function _getTotal(\DateTime $date=null) {
        $products = $products = (new POSProduct())->all();
        $total = 0.00;

        if($date) {
            // to get value of inventory in the past
            // not sure if needed, and if yes... how to go about it.
            // might be a bit complicated
            /*$start_date = Carbon::parse($date[0])->toDateString();
            $end_date = Carbon::parse($date[1])->toDateString();*/

            // on a second thought, $date should be an instance of Date() so we can retrieve the month value off it
            // the idea is to provide a given date, extract the month value and look for all sales transactions made
            // after that month. Add the quantity of items sold to the current quantity of said product.
            // Next, look in batches to see if product was restocked after that given month. If yes, for each restock
            // reduce the new current quantity by the quantity of items restocked.

            // To find the inventory value for a past month, we need to be able to know the products that were in
            // stock for that month.
            // Here is how we might be able to achieve that:
            // Note that we will only be considering products that were in stock by the end of the month. Because
            // the idea is to provide how much the inventory was worth by the end of a given month, say January.

            /*$start_month = Carbon::createFromTimestamp($date->getTimestamp())->month;
            $month_list = [];
            for($i = $start_month; count($month_list) <= 12; $i--) {
                $month_list[] = ($i == 0) ? -1 : $i ;
                // month list will most likely have negative values, which will indicate
                // an overflow into the previous year. This is acceptable
            }

            // let the least months come first (in some case, that might be a month from the previous year)
            sort($month_list);
            $year = today()->year;

            foreach ($month_list as $month) {
                if($month < 0) {
                    // If the month is in the previous year
                    // get the previous year
                    $year -= 1; // that's because the year can never be more than one year less current year
                }

                //Loop through sales_history. Identify items sold after the given month.
                //Add them back. Next look for any product purchase batch after the given month.
                //Subtract the sum of the quantity of all such batches from the new item quantity.
                //This should give us the total number of items that were in stock for that given month
                $previous_sales = [];
                $new_sales = [];
                foreach (SalesHistory::all() as $sale) {
                    if(Carbon::parse($sale->created_at)->month <= $month &&
                        Carbon::parse($sale->created_at)->year == $year) {
                        $previous_sales[] = $sale;
                    }
                }

                foreach (SalesHistory::all() as $sale) {
                    if(Carbon::parse($sale->created_at)->month <= $month &&
                        Carbon::parse($sale->created_at)->year == $year) {
                        $previous_sales[] = $sale;
                    }
                }

            }*/
        } elseif ($date == null) {
            foreach ($products as $product) {
                $total += ($product->retail_price * $product->available_stock);
            }
        }

        return $total;
    }
    #endregion

}