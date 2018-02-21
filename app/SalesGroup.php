<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SalesGroup extends Model
{
    /**
     ** The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["products", "total_amount", "amount_tendered", "change_amount",
                           "balance_due", "payment_method", "remarks", "seller", "receipt_no"];

    public function salesHistories() {
        return $this->belongsToMany(SalesHistory::class);
    }

    /**
     * @param null|array $date_range
     * @return int
     */
    public function getTotalSales($date_range = null) {
        $total = 0;
        $sales = SalesGroup::all();

        if(!$date_range) {
            $today = today()->toDateString();
            foreach ($sales as $sale) {
                if(Carbon::parse($sale->created_at)->toDateString() == $today) {
                    $total += $sale->total_amount;
                }
            }
        }

        if(is_array($date_range)) {
            $start_date = Carbon::parse($date_range[0])->toDateString();
            $end_date = Carbon::parse($date_range[1])->toDateString();

            foreach ($sales as $sale) {
                if(Carbon::parse($sale->created_at) >= $start_date
                    && Carbon::parse($sale->created_at) <= $end_date) {
                    $total += $sale->total_amount;
                }
            }
        }

        return $total;
    }
}
