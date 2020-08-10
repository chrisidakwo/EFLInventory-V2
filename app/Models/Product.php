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
 * App\Product.
 *
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string $thumb_image_path
 * @property string $slug
 * @property string $upc_code
 * @property int $category_id
 * @property int $subcategory_id
 * @property int $brand_id
 * @property string $variate_by
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand $brand
 * @property-read SubCategory $subcategory
 * @property-read Collection|ProductVariation[] $variations
 * @property-read int|null $variations_count
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereBrandId($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImagePath($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereSubcategoryId($value)
 * @method static Builder|Product whereThumbImagePath($value)
 * @method static Builder|Product whereUpcCode($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereVariateBy($value)
 * @mixin Eloquent
 */
class Product extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image_path', 'thumb_image_path', 'slug', 'upc_code', 'category_id',
        'subcategory_id', 'brand_id', 'variate_by'
    ];

    /**
     * @return BelongsTo
     */
    public function subcategory(): BelongsTo {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return HasMany
     */
    public function variations(): HasMany {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * @param array $values
     * @return bool
     */
    public static function addNewProduct(array $values): bool {
        $product = new static;
        $product->name = $values['name'];
        $product->image_path = $values['image_path'];
        $product->thumb_image_path = $values['thumb_image_path'];

        $slug = str_slug($values['name']);

        $product->slug = $slug;
        $product->upc_code = $values['barcode'];
        $product->category_id = $values['category'];
        $product->subcategory_id = $values['subcategory'];
        $product->brand_id = $values['brand'];
        $product->variate_by = $values['variate_by'];
        $product->status = 1;

        $description = "Added a new product: {$values['name']}";
        ActionHistory::addNewActionHistory($description);

        return $product->save();
    }

    /**
     * @param array $values
     * @return bool
     */
    public static function updateProduct(array $values): bool {
        $product = self::find($values['product_id']);
        $product->name = $values['name'];

        $slug = str_slug($values['name']);

        $product->slug = $slug;
        $product->upc_code = $values['barcode'];
        $product->category_id = $values['category'];
        $product->subcategory_id = $values['subcategory'];
        $product->brand_id = $values['brand'];
        $product->variate_by = $values['variate_by'];
        $product->status = 1;

        $description = "Updated product details: {$values['name']}";
        ActionHistory::AddNewActionHistory($description);

        return $product->save();
    }

    public function __toString() {
        return $this->name;
    }
}
