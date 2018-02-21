<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {

    public function product() {
        return $this->hasOne(ProductVariation::class);
    }

    public function purchase() {
        return $this->hasOne(PurchaseHistory::class);
    }
}
