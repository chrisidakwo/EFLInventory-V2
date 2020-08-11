<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\BrandDealer.
 *
 * @property int $id
 * @property int $brand_id
 * @property int $dealer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BrandDealer newModelQuery()
 * @method static Builder|BrandDealer newQuery()
 * @method static Builder|BrandDealer query()
 * @method static Builder|BrandDealer whereBrandId($value)
 * @method static Builder|BrandDealer whereCreatedAt($value)
 * @method static Builder|BrandDealer whereDealerId($value)
 * @method static Builder|BrandDealer whereId($value)
 * @method static Builder|BrandDealer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BrandDealer extends Model {
    protected $fillable = ['brand_id', 'dealer_id'];
}
