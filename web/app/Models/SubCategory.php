<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\SubCategory.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|SubCategory newModelQuery()
 * @method static Builder|SubCategory newQuery()
 * @method static Builder|SubCategory query()
 * @method static Builder|SubCategory whereCategoryId($value)
 * @method static Builder|SubCategory whereCreatedAt($value)
 * @method static Builder|SubCategory whereId($value)
 * @method static Builder|SubCategory whereName($value)
 * @method static Builder|SubCategory whereSlug($value)
 * @method static Builder|SubCategory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SubCategory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'category_id'
    ];

    /**
     * @param $name
     * @param $category_id
     * @return bool
     */
    public static function addNewSubCategory($name, $category_id): bool {
        $created = false;
        if (is_string($name) && ($name !== '')) {
            $slug = str_slug($name);
            $subcategory = new static();
            $subcategory->name = $name;
            $subcategory->slug = $slug;
            $subcategory->category_id = $category_id;

            $category = Category::find($category_id);
            $subcategory->category()->associate($category);

            $created = $subcategory->save();
        }

        return $created;
    }

    /**
     * @param $slug
     * @param $name
     * @param $category_id
     * @return bool
     */
    public static function updateSubCategory($slug, $name, $category_id): bool {
        /** @var static $subCategory */
        $subCategory = self::where('slug', '=', $slug)->first();

        if ((is_string($name)) && ($name !== '')) {
            $slug = str_slug($name);
            $subCategory->name = $name;
            $subCategory->slug = $slug;
            $subCategory->category_id = $category_id;

            // Update model relationship
            $subCategory->category()->dissociate();
            $subCategory->category()->associate($category_id);
        }

        return $subCategory->save();
    }

    /**
     * @param $slug
     * @return bool|null
     * @throws Exception
     */
    public static function deleteSubCategory($slug) {
        /** @var static $subCategory */
        $subCategory = self::where('slug', '=', $slug)->first();

        if (count($subCategory->products->toArray()) > 0) {
            return false;
        }

        $subCategory->category()->dissociate();

        return $subCategory->delete();
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
