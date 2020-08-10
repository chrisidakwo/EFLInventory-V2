<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\InventorySummary.
 *
 * @property int $id
 * @property int $month
 * @property int $year
 * @property int $total_stock_items
 * @property float $total_stock_value
 * @property int $total_damaged_items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|InventorySummary newModelQuery()
 * @method static Builder|InventorySummary newQuery()
 * @method static Builder|InventorySummary query()
 * @method static Builder|InventorySummary whereCreatedAt($value)
 * @method static Builder|InventorySummary whereId($value)
 * @method static Builder|InventorySummary whereMonth($value)
 * @method static Builder|InventorySummary whereTotalDamagedItems($value)
 * @method static Builder|InventorySummary whereTotalStockItems($value)
 * @method static Builder|InventorySummary whereTotalStockValue($value)
 * @method static Builder|InventorySummary whereUpdatedAt($value)
 * @method static Builder|InventorySummary whereYear($value)
 * @mixin Eloquent
 */
class InventorySummary extends Model {
    protected $fillable = ['id', 'month', 'year', 'total_stock_items', 'total_stock_value', 'total_damaged_items'];

//    /**
//     ** Record inventory summary for a month.
//     *
//     * @param $month
//     * @return InventorySummary
//     */
//    public static function newEntry($month): InventorySummary {
//        // that's to say if current month is January and month to be added is set for December,
//        // then reduce the year by 1 also
//        $year = ($month === 12 && today()->month === 1) ? today()->year - 1 : today()->year;
//
//        $damagedItemsCount = DamagedProducts::query()->get()->reduce(static function ($count, DamagedProducts $damagedProduct) use ($month, $year) {
//            return $count + (int) (Carbon::parse($damagedProduct->created_at)->month === $month && Carbon::parse($damagedProduct->created_at)->year === $year);
//        }, 0);
//
//        return self::create([
//            'month' => $month,
//            'year' => $year,
//            'total_stock_items' => InventoryHelper::totalItems(),
//            'total_stock_value' => InventoryHelper::total(),
//            'total_damaged_items' => $damagedItemsCount,
//            'created_at' => Carbon::create($year, $month, 1)->lastOfMonth(),
//            'updated_at' => Carbon::create($year, $month, 1)->lastOfMonth(),
//        ]);
//    }
}
