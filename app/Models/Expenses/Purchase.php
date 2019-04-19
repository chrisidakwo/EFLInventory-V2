<?php

namespace EFLInventory\Models\Expenses;

use EFLInventory\Models\Inventory\ProductVariation;
use EFLInventory\Models\Inventory\StockBatch;
use EFLInventory\Entities\RecordsPayment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends RecordsPayment {
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];
    protected $fillable = ["variation_id", "batch_id", "balance_due", "change", "payment_method", "reference",
        "remark"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariation(): BelongsTo {
        return $this->belongsTo(ProductVariation::class, "variation_id", "variation_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stockBatch(): BelongsTo {
        return $this->belongsTo(StockBatch::class, "batch_id", "id");
    }

}
