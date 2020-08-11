<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\MeasurementUnit.
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MeasurementUnit newModelQuery()
 * @method static Builder|MeasurementUnit newQuery()
 * @method static Builder|MeasurementUnit query()
 * @method static Builder|MeasurementUnit whereCreatedAt($value)
 * @method static Builder|MeasurementUnit whereId($value)
 * @method static Builder|MeasurementUnit whereName($value)
 * @method static Builder|MeasurementUnit whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MeasurementUnit extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
