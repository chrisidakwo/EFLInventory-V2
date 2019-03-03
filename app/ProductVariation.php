<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariation extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "variation_name", "image_path", "thumb_image_path", "slug", "product_id",
        "sku", "weight", "weight_unit", "color", "size", "stock", "stock_threshold", "reserved_qty"
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function batches() {
        return $this->hasMany(Batch::class);
    }

    public function orders() {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchases() {
        return $this->hasMany(PurchaseHistory::class);
    }

    public function damages() {
        return $this->hasMany(DamagedProducts::class);
    }

    public function totalPurchaseAmount(array $date_range=null) {
        $transactions = PurchaseHistory::all()->where("variation_id", "=", $this->id);

        if(!$date_range) {
            $totalAmt = $transactions->sum("total_cost");
        } else {
            $custom = $transactions->where("created_at", ">=", $date_range[0])->where("created_at", "<=", $date_range[1]);
            $totalAmt = $custom->sum("total_cost");
        }

        return floatval($totalAmt);
    }

    public function totalSalesAmount(array $date_range=null) {
        $transactions = SalesHistory::all()->where("variation_id", "=", $this->id);

        if(!$date_range) {
            $totalAmt = $transactions->sum("total_cost");
        } else {
            $custom = $transactions->where("created_at", ">=", $date_range[0])->where("created_at", "<=", $date_range[1]);
            $totalAmt = $custom->sum("total_cost");
        }

        return floatval($totalAmt);
    }

    public function AddNewVariation(array $values) {
        $variation = new ProductVariation();
        $variation->variation_name = $values["name"];
        $variation->slug = Str::slug($values["name"]);
        $variation->image_path = $values["image_path"];
        $variation->thumb_image_path = $values["thumb_image_path"];
        $variation->product_id = $values["product_id"];
        $variation->sku = $values["sku"];
        $variation->weight = $values["weight_value"];
        $variation->weight_unit = MeasurementUnit::find($values["weight_unit"])->name;
        $variation->color = $values["color"];
        $variation->size = $values["size"];
        $variation->stock = $values["stock"];
        $variation->stock_threshold = $values["stock_threshold"];

        if($variation->save()) {
            // Add action history
            $description = "Added new product variation: " . $values["name"];
            ActionHistory::AddNewActionHistory($description);

            return true;
        }

        return false;
    }

    public function UpdateVariation(array $values){
        $variation = ProductVariation::find($values["variation_id"]);
        $variation->variation_name = $values["name"];
        $variation->slug = Str::slug($values["name"]);
        $variation->sku = $values["sku"];
        $variation->weight = $values["weight_value"];
        $variation->weight_unit = MeasurementUnit::find($values["weight_unit"])->name;
        $variation->color = $values["color"];
        $variation->size = $values["size"];
        $variation->stock = $values["stock"];
        $variation->stock_threshold = $values["stock_threshold"];

        if($variation->save()) {
            // Add action history
            $description = "Updated product variation: {$values['name']}";
            ActionHistory::AddNewActionHistory($description);

            return true;
        }

        return false;
    }
}
