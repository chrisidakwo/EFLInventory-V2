<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\ProductVariation.
 *
 * @property int $id
 * @property string $variation_name
 * @property string $image_path
 * @property string $thumb_image_path
 * @property string $slug
 * @property int $product_id
 * @property string $sku
 * @property float|null $weight
 * @property string|null $weight_unit
 * @property string|null $color
 * @property string|null $size
 * @property int $stock
 * @property int $stock_threshold
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Batch[] $batches
 * @property-read int|null $batches_count
 * @property-read Collection|DamagedProducts[] $damages
 * @property-read int|null $damages_count
 * @property-read Collection|PurchaseOrder[] $orders
 * @property-read int|null $orders_count
 * @property-read Product $product
 * @property-read Collection|PurchaseHistory[] $purchases
 * @property-read int|null $purchases_count
 * @method static Builder|ProductVariation newModelQuery()
 * @method static Builder|ProductVariation newQuery()
 * @method static Builder|ProductVariation query()
 * @method static Builder|ProductVariation whereColor($value)
 * @method static Builder|ProductVariation whereCreatedAt($value)
 * @method static Builder|ProductVariation whereId($value)
 * @method static Builder|ProductVariation whereImagePath($value)
 * @method static Builder|ProductVariation whereProductId($value)
 * @method static Builder|ProductVariation whereSize($value)
 * @method static Builder|ProductVariation whereSku($value)
 * @method static Builder|ProductVariation whereSlug($value)
 * @method static Builder|ProductVariation whereStock($value)
 * @method static Builder|ProductVariation whereStockThreshold($value)
 * @method static Builder|ProductVariation whereThumbImagePath($value)
 * @method static Builder|ProductVariation whereUpdatedAt($value)
 * @method static Builder|ProductVariation whereVariationName($value)
 * @method static Builder|ProductVariation whereWeight($value)
 * @method static Builder|ProductVariation whereWeightUnit($value)
 * @mixin Eloquent
 * @property int $reserved_qty
 * @method static Builder|ProductVariation whereReservedQty($value)
 */
class ProductVariation extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variation_name', 'image_path', 'thumb_image_path', 'slug', 'product_id',
        'sku', 'weight', 'weight_unit', 'color', 'size', 'stock', 'stock_threshold', 'reserved_qty'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function batches(): HasMany {
        return $this->hasMany(Batch::class);
    }

    public function orders(): HasMany {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function damages(): HasMany {
        return $this->hasMany(DamagedProducts::class);
    }

    /**
     * @param array|null $date_range
     * @return float
     */
    public function totalPurchaseAmount(array $date_range = null): float {
        $transactions = PurchaseHistory::query()->where('variation_id', '=', $this->id)->get();

        if (!$date_range) {
            $totalAmt = $transactions->sum('total_cost');
        } else {
            $custom = $transactions->where('created_at', '>=', $date_range[0])->where('created_at', '<=', $date_range[1]);
            $totalAmt = $custom->sum('total_cost');
        }

        return (float) $totalAmt;
    }

    /**
     * @param array|null $date_range
     * @return float
     */
    public function totalSalesAmount(array $date_range = null): float {
        $transactions = SalesHistory::query()->where('variation_id', '=', $this->id)->get();

        if (!$date_range) {
            $totalAmt = $transactions->sum('total_cost');
        } else {
            $custom = $transactions->where('created_at', '>=', $date_range[0])->where('created_at', '<=', $date_range[1]);
            $totalAmt = $custom->sum('total_cost');
        }

        return (float) $totalAmt;
    }

    /**
     * @param array $values
     * @return bool
     */
    public static function addNewVariation(array $values): bool {
        $variation = new static;
        $variation->variation_name = $values['name'];
        $variation->slug = str_slug($values['name']);
        $variation->image_path = $values['image_path'];
        $variation->thumb_image_path = $values['thumb_image_path'];
        $variation->product_id = $values['product_id'];
        $variation->sku = $values['sku'];
        $variation->weight = $values['weight_value'];
        $variation->weight_unit = MeasurementUnit::find($values['weight_unit'])->name;
        $variation->color = $values['color'];
        $variation->size = $values['size'];
        $variation->stock = $values['stock'];
        $variation->stock_threshold = $values['stock_threshold'];

        if ($variation->save()) {
            // Add action history
            $description = 'Added new product variation: ' . $values['name'];
            ActionHistory::AddNewActionHistory($description);

            return true;
        }

        return false;
    }

    /**
     * @param array $values
     * @return bool
     */
    public static function updateVariation(array $values): bool {
        $variation = self::find($values['variation_id']);
        $variation->variation_name = $values['name'];
        $variation->slug = str_slug($values['name']);
        $variation->sku = $values['sku'];
        $variation->weight = $values['weight_value'];
        $variation->weight_unit = MeasurementUnit::find($values['weight_unit'])->name;
        $variation->color = $values['color'];
        $variation->size = $values['size'];
        $variation->stock = $values['stock'];
        $variation->stock_threshold = $values['stock_threshold'];

        if ($variation->save()) {
            // Add action history
            $description = "Updated product variation: {$values['name']}";
            ActionHistory::AddNewActionHistory($description);

            return true;
        }

        return false;
    }
}
