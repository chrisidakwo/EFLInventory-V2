<?php

namespace App\Http\Controllers;

use App\DamagedProducts;
use App\Helpers\InventoryHelper;
use App\Helpers\SalesHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // TODO: This should work as a cron job
        // The idea is to always enter the inventory summary for a month on the last day of the month
        // But sometimes what if the last day of the month falls on a weekend and the software is not used for 2 days or so,
        // that means that month will never get recorded. So the workaround is to retrieve the last entry and check
        // the creation date. If it is last month, good for us, break and continue. Else, begin adding the inventory
        // summary to DB before continuing.
        // Now this is some fucked up logic, but let's see if it works
        $last_entry = \DB::table("inventory_summaries")->get()->sortByDesc("created_at")->first();

        $previous_month = today()->month - 1;
        $year = today()->year;
        if($previous_month == 0) {
            $previous_month = 12;
            $year -= 1;
        }

        $last_day_previous_month = Carbon::create($year, $previous_month, 1)->lastOfMonth();
        if((today() == today()->lastOfMonth() || today() > $last_day_previous_month)
            && !$last_entry) {
            // to cover for when application is just being run for the first time
            $month = today()->month - 1;
            if($month == 0) { $month = 12;}
            $this->make_entry($month);
        }

        $last_entry = \DB::table("inventory_summaries")->get()->sortByDesc("created_at")->first();
        if(Carbon::parse($last_entry->created_at)->month != $previous_month) {
            // It means no entry for last month exists in inventory summary, then please add before continuing
            $month = today()->month - 1;
            if($month == 0) { $month = 12;}
            $this->make_entry($month);
        } elseif(today() == today()->lastOfMonth() &&
            count($last_entry->where("created_at", "=", today())) == 0) {
            $this->make_entry(today()->month);
        }

        if($request->user()->authorizeRoles(["Manager"])) {
            $today_sales = SalesHelper::getTodaySales();
            $sales_increase = SalesHelper::getSalesIncrease();
            $today_profit = SalesHelper::getProfitForToday();
            $profit_increase = SalesHelper::getProfitIncrease();
            $inventory_summary = InventoryHelper::total();
            $total_items = InventoryHelper::totalItems();
            $low_stock = InventoryHelper::lowStockProducts();
            $expired_products = InventoryHelper::expiredProducts();
            return view('home', compact("today_sales", "sales_increase", "low_stock",
                "today_profit", "profit_increase", "inventory_summary", "total_items", "expired_products"));
        }

        return view("pos.show");
    }

    /**
     ** Record inventory summary for a month
     *
     * @param $month
     */
    public function make_entry($month) {
        $year = today()->year;
        if($month == 12 && today()->month == 1) {
            // that's to say if current month is January and month to be added is set for December,
            // then reduce the year by 1 also
            $year -= 1;
        }

        $di = 0;
        foreach (DamagedProducts::all() as $item) {
            if(Carbon::parse($item->created_at)->month == $month
                && Carbon::parse($item->created_at)->year == $year) {
                $di += $item->quantity;
            }
        }

        \DB::table("inventory_summaries")->insert([
            "month" => $month,
            "year" => $year,
            "total_stock_items" => InventoryHelper::totalItems(),
            "total_stock_value" => InventoryHelper::total(),
            "total_damaged_items" => $di,
            "created_at" => Carbon::create($year, $month, 1)->lastOfMonth(),
            "updated_at" => Carbon::create($year, $month, 1)->lastOfMonth(),
        ]);
    }
}
