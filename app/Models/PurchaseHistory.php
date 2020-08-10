<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\PurchaseHistory.
 *
 * @property int $id
 * @property int $variation_id
 * @property int $batch_id
 * @property float $total_amount
 * @property float $balance_due
 * @property float $change
 * @property string $payment_method
 * @property string $remarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float $amount_paid
 * @property-read Batch|null $batch
 * @property-read ProductVariation $variation
 * @method static Builder|PurchaseHistory newModelQuery()
 * @method static Builder|PurchaseHistory newQuery()
 * @method static Builder|PurchaseHistory query()
 * @method static Builder|PurchaseHistory whereAmountPaid($value)
 * @method static Builder|PurchaseHistory whereBalanceDue($value)
 * @method static Builder|PurchaseHistory whereBatchId($value)
 * @method static Builder|PurchaseHistory whereChange($value)
 * @method static Builder|PurchaseHistory whereCreatedAt($value)
 * @method static Builder|PurchaseHistory whereId($value)
 * @method static Builder|PurchaseHistory wherePaymentMethod($value)
 * @method static Builder|PurchaseHistory whereRemarks($value)
 * @method static Builder|PurchaseHistory whereTotalAmount($value)
 * @method static Builder|PurchaseHistory whereUpdatedAt($value)
 * @method static Builder|PurchaseHistory whereVariationId($value)
 * @mixin Eloquent
 */
class PurchaseHistory extends Model {
    protected $fillable = ['id', 'variation_id', 'batch_id', 'total_amount', 'amount_paid', 'balance_due', 'change',
        'payment_method', 'remarks'];

    /**
     * @return BelongsTo
     */
    public function variation(): BelongsTo {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * @return HasOne
     */
    public function batch(): HasOne {
        return $this->hasOne(Batch::class);
    }
}
