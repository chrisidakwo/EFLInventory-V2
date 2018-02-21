<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesHistory extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["sales_group", "variation_id", "batch_id", "quantity", "unit_cost", "total_cost",
                           "profit", "loss"];

    public function product() {
        return $this->hasOne(ProductVariation::class);
    }

    public function batches() {
        return $this->belongsToMany(Batch::class);
    }

    public function salesGroup() {
        return $this->hasOne(SalesGroup::class);
    }
}
