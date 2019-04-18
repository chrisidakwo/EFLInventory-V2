<?php

namespace EFLInventory\Models\Common;

use EFLInventory\Traits\UsesStringId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes, UsesStringId;

    const TYPE_INVENTORY = "inventory";
    const TYPE_PURCHASES = "purchases";
    const TYPE_SALES = "sales";

    protected $fillable = ["parent_id", "name", "slug", "depth", "type"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Category::class, "parent_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(Category::class, "parent_id", "id");
    }
}
