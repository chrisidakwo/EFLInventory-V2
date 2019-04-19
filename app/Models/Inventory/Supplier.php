<?php

namespace EFLInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];
    protected $fillable = ["name", "phone"];
}
