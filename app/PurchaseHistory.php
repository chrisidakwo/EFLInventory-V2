<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model {

    public function variation() {
        return $this->belongsTo(ProductVariation::class);
    }

    public function batch() {
        return $this->hasOne(Batch::class);
    }
}
