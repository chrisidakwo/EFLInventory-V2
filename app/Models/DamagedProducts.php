<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\DamagedProducts.
 *
 * @property int $id
 * @property int $variation_id
 * @property int $quantity
 * @property string $damage_cause
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DamagedProducts newModelQuery()
 * @method static Builder|DamagedProducts newQuery()
 * @method static Builder|DamagedProducts query()
 * @method static Builder|DamagedProducts whereCreatedAt($value)
 * @method static Builder|DamagedProducts whereDamageCause($value)
 * @method static Builder|DamagedProducts whereDescription($value)
 * @method static Builder|DamagedProducts whereId($value)
 * @method static Builder|DamagedProducts whereQuantity($value)
 * @method static Builder|DamagedProducts whereUpdatedAt($value)
 * @method static Builder|DamagedProducts whereVariationId($value)
 * @mixin Eloquent
 */
class DamagedProducts extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['variation_id', 'quantity', 'damage_cause', 'description'];

    public function variation() {
        $this->belongsTo(ProductVariation::class);
    }
}
