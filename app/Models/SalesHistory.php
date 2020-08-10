<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\SalesHistory.
 *
 * @property int $id
 * @property int $variation_id
 * @property int $batch_id
 * @property int $sales_group_id
 * @property int $quantity
 * @property float $unit_cost
 * @property float $total_cost
 * @property float $balance_due
 * @property float $change_amount
 * @property int $is_discounted
 * @property float $discount_amount
 * @property float $profit
 * @property float $loss
 * @property int $is_wholesale
 * @property string $payment_method
 * @property string $remarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Batch[] $batches
 * @property-read int|null $batches_count
 * @property-read ProductVariation|null $product
 * @method static Builder|SalesHistory newModelQuery()
 * @method static Builder|SalesHistory newQuery()
 * @method static Builder|SalesHistory query()
 * @method static Builder|SalesHistory whereBalanceDue($value)
 * @method static Builder|SalesHistory whereBatchId($value)
 * @method static Builder|SalesHistory whereChangeAmount($value)
 * @method static Builder|SalesHistory whereCreatedAt($value)
 * @method static Builder|SalesHistory whereDiscountAmount($value)
 * @method static Builder|SalesHistory whereId($value)
 * @method static Builder|SalesHistory whereIsDiscounted($value)
 * @method static Builder|SalesHistory whereIsWholesale($value)
 * @method static Builder|SalesHistory whereLoss($value)
 * @method static Builder|SalesHistory wherePaymentMethod($value)
 * @method static Builder|SalesHistory whereProfit($value)
 * @method static Builder|SalesHistory whereQuantity($value)
 * @method static Builder|SalesHistory whereRemarks($value)
 * @method static Builder|SalesHistory whereSalesGroupId($value)
 * @method static Builder|SalesHistory whereTotalCost($value)
 * @method static Builder|SalesHistory whereUnitCost($value)
 * @method static Builder|SalesHistory whereUpdatedAt($value)
 * @method static Builder|SalesHistory whereVariationId($value)
 * @mixin Eloquent
 */
class SalesHistory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'sales_group_id', 'variation_id', 'batch_id', 'quantity', 'unit_cost', 'total_cost',
        'profit', 'loss', 'balance_due', 'change_amount', 'is_discounted', 'remarks', 'is_wholesale', 'payment_method',
        'discount_amount'];

    public function product(): HasOne {
        return $this->hasOne(ProductVariation::class);
    }

    public function batches(): BelongsToMany {
        return $this->belongsToMany(Batch::class);
    }
}
