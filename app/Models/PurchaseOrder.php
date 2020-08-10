<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\PurchaseOrder.
 *
 * @property-read ProductVariation|null $product
 * @property-read PurchaseHistory|null $purchase
 * @method static Builder|PurchaseOrder newModelQuery()
 * @method static Builder|PurchaseOrder newQuery()
 * @method static Builder|PurchaseOrder query()
 * @mixin Eloquent
 */
class PurchaseOrder extends Model {
    public function product(): HasOne {
        return $this->hasOne(ProductVariation::class);
    }

    public function purchase(): HasOne {
        return $this->hasOne(PurchaseHistory::class);
    }
}
