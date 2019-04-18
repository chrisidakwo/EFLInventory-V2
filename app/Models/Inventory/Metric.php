<?php

namespace EFLInventory\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model {
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = ["code", "name"];
}
