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
		$today = today(config('app.timezone'));

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
            $total_sales += SalesGroup::query()->whereMonth('created_at', $month)
                ->whereYear('created_at', $this_year)->sum('total_amount');

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
        $salesQuery = SalesGroup::query();

        if (is_array($date)) {
            [$startDate, $endDate] = $date;

            return $salesQuery->whereDate('created_at', '>=', Carbon::parse($startDate))
                    ->whereDate('created_at', '<=', Carbon::parse($endDate))
                    ->sum('total_amount');
        }

        return $salesQuery->whereDate('created_at', $date)->sum('total_amount');
    }

    /**
     * @param string|Carbon|array $date
     * @return float
     */
    private static function _getProfit($date): float {
        $salesQuery = SalesHistory::query();

        if (is_array($date)) {
            [$start_date, $end_date] = $date;

            return $salesQuery->where(static function (Builder $query) use ($start_date, $end_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($start_date)->format('Y-m-d'))
                    ->whereDate('created_at', '<=', Carbon::parse($end_date)->format('Y-m-d'));
            })->sum('profit');
        }

        return $salesQuery->whereDate('created_at', $date)->sum('profit');
    }
}
