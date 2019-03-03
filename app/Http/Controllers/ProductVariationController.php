<?php

namespace App\Http\Controllers;

use App\MeasurementUnit;
use App\Product;
use App\ProductVariation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManager;
use Psy\Util\Json;
use Response;

class ProductVariationController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $this->validate($request, [
                "variation-image" => "required|file|max:34748",
            ]);

            // Handle variation name
            if ($request["variation_name"] != "") {
                $variation_name = $request["variation_name"];
            } else {
                if ($request["product-weight"] != "" && $request["product-weight-unit"] != "") {
                    $unit = MeasurementUnit::find($request["product-weight-unit"]);
                    $variation_name = $request["product_name"] . " " . $request["product-weight"] . $unit->name;
                } elseif ($request["product-size"] != "") {
                    $variation_name = $request["product_name"] . " " . title_case($request["product-size"]);
                } elseif ($request["color"] != "") {
                    $variation_name = $request["product_name"] . " " . title_case($request["product-color"]);
                } else {
                    $number = random_int(1, 50);
                    $variation_name = $request["product_name"] . " Variation {$number}";
                }
            }

            $image = $request->file("variation-image");
            $image_name = str_replace(" ", "-", $variation_name) . "." . $image->getClientOriginalExtension();
            $thumb_image_name = str_replace(" ", "-", $variation_name) . "-thumb." . $image->getClientOriginalExtension();
            $saveDirectory = public_path("inventory". DIRECTORY_SEPARATOR. "products" . DIRECTORY_SEPARATOR . $request["product_name"]);

            // resize the image
            $manager = new ImageManager(array("driver" => "gd"));
            $save_image = $manager->make($image)->fit(700, 700);
            $thumb_image = $manager->make($image)->fit(250, 250);
            //$thumb_image = $thumb_image->crop(250, 250);

            try {
                \File::exists($saveDirectory) or \File::makeDirectory($saveDirectory, 0775, false, true);

                $save_image->save($saveDirectory . DIRECTORY_SEPARATOR . $image_name);
                $thumb_image->save($saveDirectory . DIRECTORY_SEPARATOR . $thumb_image_name);
            } catch (NotWritableException $e) {
            }

            $values = array(
                "name" => str_limit($variation_name),
                "image_path" => $image_name,
                "thumb_image_path" => $thumb_image_name,
                "product_id" => $request["product_id"],
                "sku" => $request["product-sku"],
                "weight_value" => $request["product-weight"] ?: "",
                "weight_unit" => $request["product-weight-unit"] ?: "",
                "color" => $request["product-color"] ?: "",
                "size" => $request["product-size"] ?: "",
                "stock" => $request["product-stock"] ?: 0,
                "stock_threshold" => $request["product-stock-threshold"] ?: 0,
            );

            // Save product variation to database
            $product = new ProductVariation();
            if ($product->AddNewVariation($values)) {
                \Session::flash("success", "Product variation has been saved successfully");
                $product = Product::find($request["product_id"]);
                return redirect()->route("editProduct", $product->slug);
            }
        }
    }

    public function update(Request $request, $slug) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $variation = new ProductVariation();
            $sVariation = $variation->all()->where("slug", "=", $slug)->first();

            $unit = MeasurementUnit::find($request["product-weight-unit"])->name;

            if ($request["variation_name"] != "") {
                $variation_name = $request["variation_name"];
            } else {
                if ($request["product-weight"] != "" && $request["product-weight-unit"] != "") {
                    $variation_name = $request["product_name"] . " " . $request["product-weight"] . $unit;
                } elseif ($request["product-size"] != "") {
                    $variation_name = $request["product_name"] . " " . title_case($request["product-size"]);
                } elseif ($request["color"] != "") {
                    $variation_name = $request["product_name"] . " " . title_case($request["product-color"]);
                } else {
                    $number = random_int(1, 50);
                    $variation_name = $request["product_name"] . " Variation {$number}";
                }
            }

            $values = array(
                "name" => $variation_name,
                "sku" => $request["product-sku"],
                "weight_value" => $request["product-weight"] ?: "",
                "weight_unit" => $request["product-weight-unit"] ?: "",
                "color" => $request["product-color"] ?: "",
                "size" => $request["product-size"] ?: "",
                "stock" => $request["product-stock"] ?: 0,
                "stock_threshold" => $request["product-stock-threshold"] ?: 0,
                "product_id" => $request["product_id"],
                "variation_id" => $sVariation->id,
            );

            if ($variation->UpdateVariation($values)) {
                \Session::flash("success", "Product variation has been updated successfully");
                $product = Product::find($sVariation->product->id);
                return back();
            }
        }
    }

    public function getVariationDetails(Request $request, $id) {
        $period = $request["period"];
        $variation = ProductVariation::find($id);
        $purchase_amt = 0.00;
        $sales_amt = 0.00;

        if($period == "all") {
            $purchase_amt = $variation->totalPurchaseAmount(null);
            $sales_amt = $variation->totalSalesAmount(null);
        } elseif ($period == "this-month") {
            $start_date = Carbon::now()->startOfMonth();
            $end_date = Carbon::now()->endOfMonth();
            $purchase_amt = $variation->totalPurchaseAmount([$start_date, $end_date]);
            $sales_amt = $variation->totalSalesAmount([$start_date, $end_date]);
        } elseif ($period == "1-month") {
            $start_date = Carbon::now()->subDays(-30);
            $end_date = Carbon::now();
            $purchase_amt = $variation->totalPurchaseAmount([$start_date, $end_date]);
            $sales_amt = $variation->totalSalesAmount([$start_date, $end_date]);

        }

        $values = array(
            "purchase_amount" => $purchase_amt,
            "sales_amount" => $sales_amt,
        );

        return Response::json($values);
    }
}
