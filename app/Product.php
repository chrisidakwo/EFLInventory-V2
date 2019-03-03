<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name", "image_path", "thumb_image_path", "slug", "upc_code", "category_id",
        "subcategory_id", "brand_id", "variate_by"
    ];

    public function subcategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function variations() {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get all products from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll() {
        return Product::all();
    }

    /**
     * @param array $values
     * @return bool
     */
    public function AddNewProduct(array $values) {
        $product = new Product();
        $product->name = $values["name"];
        $product->image_path = $values["image_path"];
        $product->thumb_image_path = $values["thumb_image_path"];

        $slug = Str::slug($values["name"]);

        $product->slug = $slug;
        $product->upc_code = $values["barcode"];
        $product->category_id = $values["category"];
        $product->subcategory_id = $values["subcategory"];
        $product->brand_id = $values["brand"];
        $product->variate_by = $values["variate_by"];
        $product->status = 1;

        $description = "Added a new product: {$values["name"]}";
        ActionHistory::AddNewActionHistory($description);

        return $product->save();
    }

    /**
     * @param array $values
     * @return mixed
     */
    public function UpdateProduct(array $values) {
        $product = Product::find($values["product_id"]);
        $product->name = $values["name"];

        $slug = Str::slug($values["name"]);

        $product->slug = $slug;
        $product->upc_code = $values["barcode"];
        $product->category_id = $values["category"];
        $product->subcategory_id = $values["subcategory"];
        $product->brand_id = $values["brand"];
        $product->variate_by = $values["variate_by"];
        $product->status = 1;

        $description = "Updated product details: {$values["name"]}";
        ActionHistory::AddNewActionHistory($description);

        return $product->save();
    }

    public function __toString(){
        return $this->name;
    }
}
