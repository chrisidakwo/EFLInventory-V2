<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SalesGroup.
 *
 * @property int $id
 * @property string $products
 * @property string $total_amount
 * @property string $amount_tendered
 * @property string $change_amount
 * @property string $balance_due
 * @property string $payment_method
 * @property string $seller
 * @property string $remarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SalesGroup newModelQuery()
 * @method static Builder|SalesGroup newQuery()
 * @method static Builder|SalesGroup query()
 * @method static Builder|SalesGroup whereAmountTendered($value)
 * @method static Builder|SalesGroup whereBalanceDue($value)
 * @method static Builder|SalesGroup whereChangeAmount($value)
 * @method static Builder|SalesGroup whereCreatedAt($value)
 * @method static Builder|SalesGroup whereId($value)
 * @method static Builder|SalesGroup wherePaymentMethod($value)
 * @method static Builder|SalesGroup whereProducts($value)
 * @method static Builder|SalesGroup whereRemarks($value)
 * @method static Builder|SalesGroup whereSeller($value)
 * @method static Builder|SalesGroup whereTotalAmount($value)
 * @method static Builder|SalesGroup whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $receipt_no
 * @method static Builder|SalesGroup whereReceiptNo($value)
 * @property int $total_quantity
 * @method static Builder|SalesGroup whereTotalQuantity($value)
 */
class SalesGroup extends Model {
    /**
     ** The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['products', 'total_quantity', 'total_amount', 'amount_tendered', 'change_amount',
        'balance_due', 'payment_method', 'remarks', 'seller', 'receipt_no'];

//    /**
//     * @param null|array $date_range
//     * @return int
//     */
//    public function getTotalSales($date_range = null) {
//        $total = 0;
//        $sales = SalesGroup::all();
//
//        if (!$date_range) {
//            $today = today()->toDateString();
//            foreach ($sales as $sale) {
//                if (Carbon::parse($sale->created_at)->toDateString() == $today) {
//                    $total += $sale->total_amount;
//                }
//            }
//        }
//
//        if (is_array($date_range)) {
//            $start_date = Carbon::parse($date_range[0])->toDateString();
//            $end_date = Carbon::parse($date_range[1])->toDateString();
//
//            foreach ($sales as $sale) {
//                if (Carbon::parse($sale->created_at) >= $start_date
//                    && Carbon::parse($sale->created_at) <= $end_date) {
//                    $total += $sale->total_amount;
//                }
//            }
//        }
//
//        return $total;
//    }
}
