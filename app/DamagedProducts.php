<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DamagedProducts extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["variation_id", "quantity", "damage_cause", "description"];

    public function variation() {
        $this->belongsTo(ProductVariation::class);
    }
}
