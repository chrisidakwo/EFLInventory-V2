<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Traits\HasSlug;
use EFLInventory\Traits\UsesStringId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model {
    use SoftDeletes, UsesStringId, HasSlug;

    protected $fillable = ["name", "slug", "phone", "address"];
}
