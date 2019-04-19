<?php

namespace EFLInventory\Models\Income;

use EFLInventory\Models\Inventory\ProductVariation;
use EFLInventory\Models\Inventory\StockBatch;
use EFLInventory\Models\POS\POSTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model {
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];
    protected $fillable = ["variation_id", "batch_id", "pos_transaction_id", "quantity", "unit cost", "total_cost",
        "balance_due", "change", "is_discounted", "discounted_amount", "profit", "is_wholesale"];

    protected $casts = [
        "is_discounted" => "bool",
        "is_wholesale" => "bool"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariation(): BelongsTo {
        return $this->belongsTo(ProductVariation::class, "variation_id", "variation_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posTransaction(): BelongsTo {
        return $this->belongsTo(POSTransaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stockBatch(): BelongsTo {
        return $this->belongsTo(StockBatch::class, "batch_id");
    }
}
