<?php

namespace EFLInventory\Models\Inventory;

use EFLInventory\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model {
    use SoftDeletes, HasSlug;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];
    protected $fillable = ["name", "slug", "phone", "address"];
}
