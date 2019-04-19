<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Entities\HasStringID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends HasStringID {
    use SoftDeletes;

    protected $fillable = ["name", "category_id", "upc_code", "brand_id", "image_path", "thumb_image_path", "status"];
    protected $casts = [
        "status" => "bool"
    ];

    protected $with = ["variations"];
    protected $withCount = ["variations"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(ProductVariation::class, "variation_id", "variation_id");
    }
}
