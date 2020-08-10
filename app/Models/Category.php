<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Category.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read Collection|SubCategory[] $subcategories
 * @property-read int|null $subcategories_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    /**
     * Create a new category.
     *
     * @param $name
     * @return static
     */
    public static function addNewCategory($name): self {
        $slug = str_slug($name);

        return self::create([
                'name' => $name,
                'slug' => $slug
            ]);
    }

    /**
     * @param $slug
     * @param $name
     * @return bool
     */
    public static function updateCategory($slug, $name): bool {
        /** @var static $category */
        $category = self::where('slug', '=', $slug)->firstOrFail();

        if ((is_string($name)) && ($name !== '')) {
            $slug = str_slug($name);
            $category->name = $name;
            $category->slug = $slug;
        }

        return $category->save() !== null;
    }

    /**
     * @param $slug
     * @return bool|null
     * @throws Exception
     */
    public static function deleteCategory($slug) {
        /** @var static $category */
        $category = self::where('slug', '=', $slug)->firstOrFail();

        if (count($category->products->toArray()) > 0) {
            return false;
        }

        if (count($category->subcategories->toArray()) > 0) {
            return false;
        }

        return $category->delete();
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
