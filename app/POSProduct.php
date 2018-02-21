<?php

namespace App;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class POSProduct extends Collection {
    public $key;
    public $product_name;
    public $product_id;
    public $product_slug;
    public $variation_id;
    public $batch_id;
    public $variation_name;
    public $variation_img;
    public $variation_img_thumb;
    public $upc_code;
    public $sku;
    public $brand;
    public $category;
    public $sub_category;
    public $retail_price;
    public $wholesale_price;
    public $available_stock;
    public $variate_by;
    public $weight;
    public $weight_unit;
    public $color;
    public $size;
    public $expiry_date;

    public function __construct() {
        $this->key = Uuid::uuid4();
        parent::__construct();
    }

    public function all() {
        $this->addAll();
        return $this->items;
    }

    protected function addAll() {
        $variations = ProductVariation::all();
        $batches = Batch::all();

        foreach($variations as $variation) {
            $pos_product = new POSProduct();

            foreach($batches as $batch) {
                if($batch->variation_id == $variation->id && $batch->on_sale == 1) {
                    $pos_product->retail_price = $batch->retail_price;
                    $pos_product->wholesale_price = $batch->wholesale_price;
                    $pos_product->expiry_date = $batch->expiry_date;

                    $pos_product->product_name = $variation->product->name;
                    $pos_product->product_id = $variation->product_id;
                    $pos_product->product_slug = $variation->product->slug;
                    $pos_product->variation_id = $variation->id;
                    $pos_product->batch_id = $batch->id;
                    $pos_product->variation_name = $variation->variation_name;
                    $pos_product->variation_img = $variation->image_path;
                    $pos_product->variation_img_thumb = $variation->thumb_image_path;
                    $pos_product->category = $variation->product->subcategory->category->name;
                    $pos_product->sub_category = $variation->product->subcategory->name;
                    $pos_product->upc_code = $variation->product->upc_code;
                    $pos_product->brand = $variation->product->brand;
                    $pos_product->sku = $variation->sku;
                    $pos_product->variate_by = $variation->product->variate_by;
                    $pos_product->weight = $variation->weight . $variation->weight_unit ;
                    $pos_product->size = $variation->size;
                    $pos_product->color = $variation->color;
                    $pos_product->available_stock = ($variation->stock - $variation->reserved_qty);

                    $this->push($pos_product);
                }
            }
        }
    }

    /**
     * @param $variation_id
     * @return POSProduct
     */
    public function findByVariation($variation_id) {
        $variation = ProductVariation::find($variation_id);
        $batch = Batch::all()->where("variation_id", "=", $variation_id)->where("on_sale", 1)->first();

        $pos_product = new POSProduct();
        $pos_product->product_name = $variation->product->name;
        $pos_product->product_id = $variation->product_id;
        $pos_product->product_slug = $variation->product->slug;
        $pos_product->variation_id = $variation->id;
        $pos_product->batch_id = $batch->id;
        $pos_product->variation_name = $variation->variation_name;
        $pos_product->variation_img = $variation->image_path;
        $pos_product->variation_img_thumb = $variation->thumb_image_path;
        $pos_product->category = $variation->product->subcategory->category->name;
        $pos_product->sub_category = $variation->product->subcategory->name;
        $pos_product->upc_code = $variation->product->upc_code;
        $pos_product->brand = $variation->product->brand;
        $pos_product->sku = $variation->sku;
        $pos_product->variate_by = $variation->product->variate_by;
        $pos_product->weight = $variation->weight . $variation->weight_unit ;
        $pos_product->weight_unit = $variation->weight_unit ;
        $pos_product->size = $variation->size;
        $pos_product->color = $variation->color;
        $pos_product->available_stock = $variation->stock;

        $pos_product->retail_price = $batch->retail_price;
        $pos_product->wholesale_price = $batch->wholesale_price;
        $pos_product->expiry_date = $batch->expiry_date;

        return $pos_product;

    }


    /**
     * @param $batch_id
     * @param int $on_sale
     * @return POSProduct
     */
    public function findByBatch($batch_id, $on_sale = 1) {
        $batch = Batch::find($batch_id)->where("on_sale", $on_sale)->first();
        $variation = ProductVariation::find($batch->variation_id);

        $pos_product = new POSProduct();
        $pos_product->product_name = $variation->product->name;
        $pos_product->product_id = $variation->product_id;
        $pos_product->product_slug = $variation->product->slug;
        $pos_product->variation_id = $variation->id;
        $pos_product->batch_id = $batch->id;
        $pos_product->variation_name = $variation->variation_name;
        $pos_product->variation_img = $variation->image_path;
        $pos_product->variation_img_thumb = $variation->thumb_image_path;
        $pos_product->category = $variation->product->subcategory->category->name;
        $pos_product->sub_category = $variation->product->subcategory->name;
        $pos_product->upc_code = $variation->product->upc_code;
        $pos_product->brand = $variation->product->brand;
        $pos_product->sku = $variation->sku;
        $pos_product->variate_by = $variation->product->variate_by;
        $pos_product->weight = $variation->weight . $variation->weight_unit ;
        $pos_product->weight_unit = $variation->weight_unit ;
        $pos_product->size = $variation->size;
        $pos_product->color = $variation->color;
        $pos_product->available_stock = $variation->stock;

        $pos_product->retail_price = $batch->retail_price;
        $pos_product->wholesale_price = $batch->wholesale_price;
        $pos_product->expiry_date = $batch->expiry_date;

        return $pos_product;
    }

    /**
     * @param $product_id
     * @return array
     */
    public function findByProduct($product_id) {
        $variations = ProductVariation::all()->where("product_id", $product_id)->all();
        $pos_products = array();

        foreach ($variations as $variation) {
            $batch = Batch::all()->where("variation_id", "=", $variation->id)->where("on_sale", 1)->first();
            $pos_products[] = [
                "product_name" => strval($variation->product->name),
                "product_id" => $variation->product_id,
                "product_slug" => $variation->product->slug,
                "variation_id" => $variation->id,
                "batch_id" => $batch->id,
                "variation_name" => $variation->variation_name,
                "variation_img" => $variation->image_path,
                "variation_img_thumb" => $variation->thumb_image_path,
                "category" => $variation->product->subcategory->category->name,
                "sub_category" => $variation->product->subcategory->name,
                "upc-code" => $variation->product->upc_code,
                "brand" => $variation->product->brand,
                "sku" => $variation->sku,
                "variate_by" => $variation->product->variate_by,
                "weight" => $variation->weight . $variation->weight_unit,
                "weight_unit" => $variation->weight_unit,
                "size" => $variation->size,
                "color" => $variation->color,
                "available_stock" => $variation->stock,
                "retail_price" => $batch->retail_price,
                "wholesale_price" => $batch->wholesale_price,
                "expiry_date" => $batch->expiry_date
            ];
        }

        return $pos_products;
    }
}
