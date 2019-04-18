<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Traits\UsesStringId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {
    use SoftDeletes, UsesStringId;

    protected $fillable = ["name", "phone"];
}
