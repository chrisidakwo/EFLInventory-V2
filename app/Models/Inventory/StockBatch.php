<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Models\Income\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBatch extends Model {
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];

    protected $fillable = ["variation_id", "ordered_quantity", "previous_inventory_quantity",
        "current_inventory_quantity", "unit_cost", "retail_price", "wholesale_price", "on_sale", "expiry_date"];

    protected $dates = ["expiry_date"];
    protected $casts = [
        "on_sale" => "bool"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariation(): BelongsTo {
        return $this->belongsTo(ProductVariation::class, "variation_id", "variation_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): HasMany {
        return $this->hasMany(Sales::class, "batch_id", "id");
    }
}
