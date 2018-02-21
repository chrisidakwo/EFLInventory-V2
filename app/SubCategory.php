<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', "slug", 'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function products() {
        return $this->hasMany(Product::class, "subcategory_id");
    }

    /**
     * Get all sub categories from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll() {
        return SubCategory::all();
    }

    public function AddNewSubCategory($name, $category_id) {
        $created = false;
        if(is_string($name) && ($name != "")) {
            $slug = Str::slug($name);
            $subcategory = new SubCategory();
            $subcategory->name = $name;
            $subcategory->slug = $slug;
            $subcategory->category_id = $category_id;

            $category = Category::find($category_id);
            $subcategory->category()->associate($category);

            $created = $subcategory->save();
        }

        return $created;
    }

    public function UpdateSubCategory($slug, $name, $category_id) {
        $subCategory = (new SubCategory())->getAll()->where("slug", "=", $slug)->first();

        if((is_string($name)) && ($name != "")) {
            $slug = Str::slug($name);
            $subCategory->name = $name;
            $subCategory->slug = $slug;
            $subCategory->category_id = $category_id;

            // Update model relationship
            $subCategory->category()->dissociate();
            $subCategory->category()->associate($category_id);
        }

        return $subCategory->save() != null;
    }

    public function DeleteSubCategory($slug) {
        $subCategory = (new SubCategory())->getAll()->where("slug", "=", $slug)->first();

        if(count($subCategory->products->toArray()) > 0) {
            return false;
        }

        $subCategory->category()->dissociate();
        return $subCategory->delete();
    }
}
