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
 * App\Brand.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $contact_info
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Dealer[] $dealers
 * @property-read int|null $dealers_count
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|Brand newModelQuery()
 * @method static Builder|Brand newQuery()
 * @method static Builder|Brand query()
 * @method static Builder|Brand whereAddress($value)
 * @method static Builder|Brand whereContactInfo($value)
 * @method static Builder|Brand whereCreatedAt($value)
 * @method static Builder|Brand whereId($value)
 * @method static Builder|Brand whereName($value)
 * @method static Builder|Brand whereSlug($value)
 * @method static Builder|Brand whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Brand extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'contact_info', 'address',
    ];

    /**
     * @param array $values
     * @return bool
     */
    public static function addNewBrand(array $values): bool {
        $created = false;
        if (is_string($values['name']) && ($values['name'] !== '')) {
            $slug = str_slug($values['name']);
            $brand = new static();
            $brand->name = $values['name'];
            $brand->slug = $slug;
            $brand->contact_info = $values['contact'];
            $brand->address = $values['address'];

            $created = $brand->save();
        }

        return $created;
    }

    /**
     * Update a brand.
     *
     * @param array $values
     * @return bool. True if successful; false otherwise
     */
    public static function updateBrand(array $values): bool {
        $updated = false;
        $name = $values['name'];
        $slug = $values['slug'];
        /** @var static $brand */
        $brand = self::where('slug', '=', $slug)->firstOrFail();

        if ((is_string($name)) && ($name !== '')) {
            $slug = str_slug($name);
            $brand->name = $name;
            $brand->contact_info = $values['contact'];
            $brand->address = $values['address'];
            $brand->slug = $slug;

            $updated = ($brand->save()) ?: true;
        }

        return $updated;
    }

    /**
     * Returns true if item is deleted; false otherwise.
     *
     * @param $slug
     * @return bool
     * @throws Exception
     */
    public static function deleteBrand($slug): bool {
        /** @var static $brand */
        $brand = self::where('slug', '=', $slug)->firstOrFail();

        $products = count($brand->products);
        if ($products > 0) {
            return false;
        }

        return $brand->delete();
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
