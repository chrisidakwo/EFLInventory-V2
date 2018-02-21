<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
