<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function subcategories() {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll() {
        return Category::all();
    }

    /**
     * Create a new category
     *
     * @param $name
     * @return bool
     */
    public function AddNewCategory($name) {
        $created = false;
        if((!empty($name)) && is_string($name) && ($name != "")) {
            $slug = Str::slug($name);
            $created = Category::create(array(
                "name" => $name,
                "slug" => $slug
            ));
        }

        return $created;
    }

    public function UpdateCategory($slug, $name) {
        $category = (new Category())->getAll()->where("slug", "=", $slug)->first();

        if((is_string($name)) && ($name != "")) {
            $slug = Str::slug($name);
            $category->name = $name;
            $category->slug = $slug;
        }

        return $category->save() != null;
    }

    public function DeleteCategory($slug) {
        $category = (new Category())->getAll()->where("slug", "=", $slug)->first();

        if(count($category->products->toArray()) > 0) {
            return false;
        } elseif (count($category->subcategories->toArray()) > 0) {
            return false;
        }

        return $category->delete();
    }
}
