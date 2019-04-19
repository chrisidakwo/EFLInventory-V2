<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Models\Income\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model {
    use SoftDeletes;

    protected $primaryKey = "variation_id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = ["variation_id", "generic_name", "product_id", "sku", "variation_type",
        "variation_type_value", "variation_type_unit", "current_stock", "stock_threshold"];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): HasMany {
        return $this->hasMany(Sales::class, "variation_id", "variation_id");
    }
}
