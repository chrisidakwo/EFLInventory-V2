<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Dealer.
 *
 * @property int $id
 * @property string $full_name
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Brand[] $brands
 * @property-read int|null $brands_count
 * @method static Builder|Dealer newModelQuery()
 * @method static Builder|Dealer newQuery()
 * @method static Builder|Dealer query()
 * @method static Builder|Dealer whereCreatedAt($value)
 * @method static Builder|Dealer whereFullName($value)
 * @method static Builder|Dealer whereId($value)
 * @method static Builder|Dealer wherePhone($value)
 * @method static Builder|Dealer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Dealer extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'phone'
    ];

    /**
     * Add a new dealer to database.
     *
     * @param array $values
     * @return bool. Returns true if added successfully; false otherwise
     */
    public static function addNewDealer(array $values): bool {
        $created = false;
        if (is_string($values['full_name']) && ($values['full_name'] !== '')) {
            $dealer = new static();
            $dealer->full_name = $values['full_name'];
            $dealer->phone = $values['phone'] ?: '';
            $created = $dealer->save();

            // Associate relationship
            $brands = $values['brands'];
            if (!is_array($brands) && is_string($brands)) {
                $brands = explode(',', $brands);
            }

            foreach ($brands as $brand) {
                $b = Brand::whereId($brand)->first();
                if ($b) {
                    $dealer->brands()->attach(Brand::find($b->id));
                }
            }
        }

        return $created;
    }

    /**
     * @return BelongsToMany
     */
    public function brands(): BelongsToMany {
        return $this->belongsToMany(Brand::class, 'brand_dealers');
    }

    /**
     * Update an existing dealer.
     *
     * @param array $values
     * @return bool
     */
    public static function updateDealer(array $values): bool {
        $dealer_id = $values['dealer_id'];
        $full_name = $values['full_name'];
        $brands = $values['brands'];
        $phone = $values['phone'];

        /** @var static $dealer */
        $dealer = self::where('id', $dealer_id)->firstOrFail();
        $dealer->full_name = $full_name;
        $dealer->phone = $phone ?: '';

        $dealer->brands()->detach();

        foreach ($brands as $brand) {
            /*$brand = BrandDealer::create([
                "brand_id" => $brand,
                "dealer_id" => $dealer->id
            ]);*/

            $dealer->brands()->attach(Brand::find($brand));
        }

        return ($dealer->save()) ?: true;
    }

    /**
     * Delete a dealer from the database.
     *
     * @param $id
     * @return mixed. Returns true if successful; false otherwise; null if dealer doesn't exist
     * @throws Exception
     */
    public static function deleteDealer($id) {
        return self::query()->whereId($id)->delete();
    }
}
