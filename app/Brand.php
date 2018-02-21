<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'contact_info', "address",
    ];

    public function dealers() {
        return $this->belongsToMany(Dealer::class, "brand_dealers");
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all brands from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll() {
        return Brand::all();
    }

    public function AddNewBrand(array $values) {
        $created = false;
        if(is_string($values["name"]) && ($values["name"] != "")) {
            $slug = Str::slug($values["name"]);
            $brand = new Brand();
            $brand->name = $values["name"];
            $brand->slug = $slug;
            $brand->contact_info = $values["contact"];
            $brand->address = $values["address"];

            $created = $brand->save();
        }

        return $created;
    }

    /**
     * Update a brand
     *
     * @param array $values
     * @return bool. True if successful; false otherwise
     */
    public function UpdateBrand(array $values) {
        $updated = false;
        $name = $values["name"];
        $slug = $values["slug"];
        $brand = new Brand();
        $brand = $brand->getAll()->where("slug", "=", $slug)->first();

        if((is_string($name)) && ($name != "")) {
            $slug = Str::slug($name);
            $brand->name = $name;
            $brand->contact_info = $values["contact"];
            $brand->address = $values["address"];
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
     */
    public function DeleteBrand($slug) {
        $brand = new Brand();
        $brand = $brand->getAll()->where("slug", "=", $slug)->first();

        $products = count($brand->products);
        if ($products > 0) {
            return false;
        }

        return $brand->delete();
    }

    public function __toString(){
        return $this->name;
    }
}
