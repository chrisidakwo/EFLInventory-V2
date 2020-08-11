<?php
/**
 * Class SalesHelper.
 *
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
        $startOfMonth = today(config('app.timezone'))->startOfMonth();
        $endOfMonth = today(config('app.timezone'))->endOfMonth();

        return self::_getSales([$startOfMonth, $endOfMonth]);
    }

    /**
     ** get sales for last month.
     *
     * @return float
     */
    public static function getLastMonthSales(): float {
        $startOfLastMonth = today(config('app.timezone'))->subMonth()->startOfMonth();
        $endOfLastMonth = today(config('app.timezone'))->subMonth()->endOfMonth();

        return self::_getSales([$startOfLastMonth, $endOfLastMonth]);
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
            $total_sales += SalesGroup::query()->whereRaw("MONTH(created_at) = $month")
                ->whereRaw("YEAR(created_at) = $this_year")->sum('total_amount');

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
        return (float) SalesGroup::query()->when(is_array($date), static function (Builder $builder) use ($date) {
            $startDate = !($date[0] instanceof Carbon) ? Carbon::parse($date[0])->toDateString(): $date[0]->toDateTimeString();
            $endDate = !($date[1] instanceof Carbon) ? Carbon::parse($date[1])->toDateString(): $date[1]->toDateTimeString();

            return $builder->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }, static function (Builder $builder) use ($date) {
            return $builder->whereDate('created_at', $date);
        })->sum('total_amount');
    }

    /**
     * @param string|Carbon|array $date
     * @return float
     */
    private static function _getProfit($date): float {
        $salesQuery = SalesHistory::query();

        return (float) $salesQuery->when(is_array($date), static function (Builder $query) use ($date) {
            $start_date = is_string($date[0]) ? Carbon::parse($date[0]): $date[0];
            $end_date = is_string($date[1]) ? Carbon::parse($date[1]): $date[1];

            return $query->where(static function (Builder $query) use ($start_date, $end_date) {
                $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            }, static function (Builder $query) use ($date) {
                $date = is_string($date) ? Carbon::parse($date) : $date;

                return $query->whereDate('created_at', $date);
            });
        })->sum('profit');
    }
}
