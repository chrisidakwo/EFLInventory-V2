<?php
/**
 * Class SalesHelper.
 *
 * Description Here
 *
 * @author: Ekojoka Christolight Idakwo <chris.idakwo@gmail.com>
 * @date: 26/12/2017
 * @time: 04:01
 * @package: App\Helpers;
 */

namespace App\Helpers;

use App\Models\SalesGroup;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SalesHelper {
    // TODO: Refactor methods. Reduce code reuse.

    /**
     ** get total sales for today.
     *
     * @return int
     */
    public static function getTodaySales() {
        $today = Carbon::today()->toDateString();

        return self::_getSales($today);
    }

    /**
     ** get total sales for yesterday.
     *
     * @return int
     */
    public static function getYesterdaySales() {
        $yesterday = Carbon::yesterday()->toDateString();

        return self::_getSales($yesterday);
    }

    /**
     ** get today's sales percentage increase relative to yesterday.
     *
     * @return float|int
     */
    public static function getSalesIncrease() {
        $today = self::getTodaySales();
        $yesterday = self::getYesterdaySales();

        if ((int) $yesterday === 0) {
            return 0;
        }

        /*if((int)$yesterday == 0 && $today > 0) {
            return $today;
        } elseif ((int)$yesterday == 0 && $today == 0) {
            return 0;
        }*/

        return (($today - $yesterday) / $yesterday) * 100;
    }

    /**
     ** get total sales profit for today.
     *
     * @return float
     */
    public static function getProfitForToday(): float {
        $today = Carbon::today()->toDateString();

        return self::_getProfit($today);
    }

    /**
     ** get total sales profit for yesterday.
     *
     * @return float
     */
    public static function getProfitForYesterday(): float {
        $yesterday = Carbon::yesterday()->toDateString();

        return self::_getProfit($yesterday);
    }

    /**
     ** get today's profit percentage increase relative to yesterday.
     *
     * @return float|int
     */
    public static function getProfitIncrease() {
        $today = self::getProfitForToday();
        $yesterday = self::getProfitForYesterday();

        if ((int) $yesterday === 0) {
            return 0;
        }

        return (($today - $yesterday) / $yesterday) * 100;
    }

    /**
     ** get sales for this month.
     *
     * @return float
     */
    public static function getMonthSales(): float {
        $total_sales = 0.00;
        $sales = SalesGroup::all();
        $this_month = Carbon::now()->month;
        $this_year = Carbon::now()->year;

        foreach ($sales as $sale) {
            $sale_month = Carbon::parse($sale->created_at)->month;
            $sale_year = Carbon::parse($sale->created_at)->year;
            if ($sale_month === $this_month && $sale_year === $this_year) {
                $total_sales += (float) $sale->total_amount;
            }
        }

        return $total_sales;
    }

    /**
     ** get sales for last month.
     *
     * @return float
     */
    public static function getLastMonthSales(): float {
        $total_sales = 0.00;
        $sales = SalesGroup::all();
        $last_month = today('Africa/Lagos')->month - 1;
        $this_year = today('Africa/Lagos')->year;

        foreach ($sales as $sale) {
            $sale_month = Carbon::parse($sale->created_at)->month;
            $sale_year = Carbon::parse($sale->created_at)->year;
            if ($sale_month === $last_month && $sale_year === $this_year) {
                $total_sales += (float) $sale->total_amount;
            }
        }

        return $total_sales;
    }

    /**
     ** get total sales for a given months.
     *
     * @param $start_date
     * @return array
     */
    public static function getSalesRange($start_date): array {
        $sales_range = [];
        $total_sales = 0.00;
        $sales = SalesGroup::all();
        $start_month = Carbon::parse($start_date)->month;
        $this_year = today('Africa/Lagos')->year;

        // Based on the idea that the business year begins in January.
        // Suppose $start_month = 1, that means the $month_list array will only have month 1 which is January
        // hence the chart will only show total sales for January, once the month moves to February,
        // the chart will only show total sales for February and January. So it continues until December.
        // The loop continues as such.
        // TODO: Improve algorithm

        // LOL! Damn, Chris. Why not just use the _getSales function below, and provide the $date as an array where
        // first and last value are the given month or months

        $month_list = [];
        for ($i = $start_month; $i > 0; $i--) {
            $month_list[] = $i;
        }

        sort($month_list);
        foreach ($month_list as $month) {
            foreach ($sales as $sale) {
                $sale_month = Carbon::parse($sale->created_at)->month;
                $sale_year = Carbon::parse($sale->created_at)->year;
                if ($sale_month === $month && $sale_year === $this_year) {
                    $total_sales += (float) $sale->total_amount;
                }
            }

            $sales_range[] = $total_sales;
            $total_sales = 0.00;
        }

        return $sales_range;
    }

    /**
     * @param string|Carbon|array $date
     * @return float
     */
    private static function _getSales($date): float {
        // $total = 0;
        $total = (float) SalesGroup::query()->when(is_array($date), static function (Builder $builder) use ($date) {
            $startDate = Carbon::parse($date[0])->toDateString();
            $endDate = Carbon::parse($date[1])->toDateString();

            return $builder->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }, static function (Builder $builder) use ($date) {
            return $builder->whereDate('created_at', $date);
        })->sum('total_amount');

        // If date is a date range
        // if (is_array($date)) {
        //     $start_date = Carbon::parse($date[0])->toDateString();
        //     $end_date = Carbon::parse($date[1])->toDateString();

        //     foreach ($sales as $sale) {
        //         if (Carbon::parse($sale->created_at) >= $start_date
        //             && Carbon::parse($sale->created_at) <= $end_date) {
        //             $total += $sale->total_amount;
        //         }
        //     }
        // } else {
        //     // If not a date range
        //     foreach ($sales as $sale) {
        //         if (Carbon::parse($sale->created_at)->toDateString() == $date) {
        //             $total += $sale->total_amount;
        //         }
        //     }
        // }

        return $total;
    }

    /**
     * @param string|Carbon|array $date
     * @return float
     */
    private static function _getProfit($date): float {
        $sales = SalesHistory::all();
        $profit = 0.00;

        switch ($date) {
            case is_array($date):
                $start_date = Carbon::parse($date[0]);
                $end_date = Carbon::parse($date[1]);

                foreach ($sales as $sale) {
                    $saleCreatedAt = Carbon::parse($sale->created_at);
                    if ($saleCreatedAt->clone()->greaterThanOrEqualTo($start_date)
                        && $saleCreatedAt->clone()->lessThanOrEqualTo($end_date)) {
                        $profit += (float) $sale->profit;
                    }
                }

                break;

            case !is_array($date):
            default:
                $date = is_string($date) ? Carbon::parse($date): $date;
                foreach ($sales as $sale) {
                    if (Carbon::parse($sale->created_at)->toDateString() == $date) {
                        $profit += (float) $sale->profit;
                    }
                }

                break;
        }

        return $profit;
    }
}
