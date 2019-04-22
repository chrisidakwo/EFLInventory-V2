<?php

namespace EFLInventory\Models\Common;

use EFLInventory\Entities\HasStringID;
use EFLInventory\Models\Expenses\Purchase;
use EFLInventory\Models\Income\Sales;
use EFLInventory\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends HasStringID {
    use SoftDeletes;

    public const TYPE_INVENTORY = "inventory";
    public const TYPE_PURCHASES = "purchases";
    public const TYPE_SALES = "sales";

    protected $fillable = ["parent_id", "name", "slug", "depth", "type"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo {
        return $this->belongsTo(__CLASS__, "parent_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany {
        return $this->hasMany(__CLASS__, "parent_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class)->where("type", self::TYPE_INVENTORY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases(): HasMany {
        return $this->hasMany(Purchase::class)->where("type", self::TYPE_PURCHASES);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): HasMany {
        return $this->hasMany(Sales::class)->where("type", self::TYPE_SALES);
    }
}
