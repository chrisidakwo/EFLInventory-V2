<?php

namespace App\Http\Controllers;

use App\ActionHistory;
use App\Batch;
use App\Brand;
use App\Category;
use App\DamagedProducts;
use App\MeasurementUnit;
use App\POSProduct;
use App\Product;
use App\ProductVariation;
use App\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManager;
use Response;

class ProductController extends Controller {

    /**
     * ProductController constructor.
     */
    public function __construct() {
        $this->middleware("auth");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $products = new POSProduct();
            $products = $products->all();
            return view("products.show", compact('products'));
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    /**
     ** find a product and its variations using the product sku/barcod
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function barcodeFind(Request $request) {
        $barcode = $request->get("barcode");
        $product = null;
        $variations = null;
        if($barcode != "") {
            $product = Product::all()->where("upc_code", $barcode)->first();
            $variations = (new POSProduct())->findByProduct($product->id);

            return Response::json(["product" => $product, "variations" => $variations]);
        }

        return Response::json(["product" => "Barcode is null or empty"]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $categories = Category::all()->sortBy("name");
            $brands = Brand::all()->sortBy("name");
            $subCategories = SubCategory::all()->sortBy("name");
            $products = Product::all();
            return view("products.create", compact("categories", "brands", "subCategories", "products"));
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->validate($request, array(
            "name" => "required|unique:products|max:50",
            "product-image" => "required|image|mimes:jpeg,png,jpg|max:2048",
        ));

        $image = $request->file("product-image");
        $prefix = str_replace(" ", "-", $request["name"]);
        $image_name = "{$prefix}.{$image->getClientOriginalExtension()}";
        $thumb_image_name = "{$prefix}-thumb.{$image->getClientOriginalExtension()}";
        $saveDirectory = public_path("inventory\\products\\" . $request["name"]);

        // resize the image
        $manager = new ImageManager(array("driver" => "gd"));
        $image = $manager->make($image)->fit(700, 700);
        $thumb_image = $manager->make($image)->fit(250, 250);

        try{
            \File::exists($saveDirectory) or \File::makeDirectory($saveDirectory, 0775, false, true);

            $image->save(($saveDirectory . "\\" . $image_name));
            $thumb_image->save(($saveDirectory . "\\" . $thumb_image_name));
        } catch(NotWritableException $e) {
        }

        // Retrieve data from POST request
        $values = array(
            "name" => $request["name"],
            "category" => $request["product-category"],
            "subcategory" => $request["product-subcategory"],
            "brand" => $request["product-brand"],
            "barcode" => $request["product-barcode"] ?: "",
            "image_path" => $image_name,
            "thumb_image_path" => $thumb_image_name,
            "variate_by" => $request["product-variate-by"]
        );

        // Save product to database
        $product = new Product();
        if($product->AddNewProduct($values)) {
            $description = "Added new product: " . title_case($values["name"]);
            ActionHistory::AddNewActionHistory($description);
            \Session::flash("success", "Product has been saved successfully");
            $product = Product::all()->where("name", $request["name"])->first();
            return redirect()->route("editProduct", $product->slug);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $this->validate($request, array(
//            "name" => "required|unique:products|max:50",
        ));

        // Retrieve data from POST request
        $values = array(
            "name" => $request["name"],
            "category" => $request["product-category"],
            "subcategory" => $request["product-subcategory"],
            "brand" => $request["product-brand"],
            "barcode" => $request["product-barcode"] ?: "",
            "variate_by" => $request["product-variate-by"],
            "product_id" => $request["product_id"]
        );

        // Save product to database
        $product = new Product();
        if($product->UpdateProduct($values)) {
            $description = "Updated product: " . title_case($values["name"]);
            ActionHistory::AddNewActionHistory($description);
            \Session::flash("success", "Product has been updated successfully");
            $product = Product::all()->where("name", $request["name"])->first();
            return redirect()->route("editProduct", $product->slug);
        }
    }

    /**
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $slug) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $categories = Category::all()->sortBy("name");
            $brands = Brand::all()->sortBy("name");
            $subCategories = SubCategory::all()->sortBy("name");
            $product = Product::all()->where("slug", $slug)->first();
            $weight_units = MeasurementUnit::all()->sortBy("name");
            return view("products.edit", compact("categories", "brands",
                "subCategories", "product", "weight_units"));
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    /**
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reserve(Request $request, $slug) {
        $product = ProductVariation::where("slug", $slug)->first();
        if($request["quantity"] > $product->stock) {
            \Session::flash("error", "You cannot reserve more than the available stock!");
        } else {
            $product->reserved_qty = $request["quantity"];
            $product->save();
            \Session::flash("success", "{$request['quantity']} of {$product->variation_name} has added to your reserve.");
        }

        return back();
    }


    /**
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function record_damaged(Request $request, $slug) {
        $cause = $request["cause"] ?: "";
        $description = $request["description"] ?: "";
        $product = ProductVariation::where("slug", $slug)->first();
        $damaged_qty = $request["damaged_quantity"];
        $count = 0;


        if($damaged_qty > $product->stock) {
            \Session::flash("error", "Damaged items cannot be more than the available stock!");
        } else {
            // find the product batch based on the expiry date
            $expiry_date = Carbon::parse($request["batch"])->toDateString();
            $batches = Batch::all()->where("variation_id", $product->id);

            foreach ($batches as $batch) {
                $count++;
                if (Carbon::parse($batch->expiry_date)->toDateString() == $expiry_date) {
                    $batch->current_quantity -= $damaged_qty;
                    $batch->save();

                    // Do same for the product
                    $product->stock -= $damaged_qty;
                    $product->save();

                    DamagedProducts::create([
                        "variation_id" => $product->id,
                        "quantity" => $damaged_qty,
                        "damage_cause" => $cause,
                        "description" => $description
                    ]);

                    \Session::flash("success", "Damages have been recorded");
                    break;
                } else {
                    if($count == count($batches)) {
                        \Session::flash("error", "Could not find a batch of {$product->variation_name} with the given expiry date.");
                    }
                }

            }
        }

        return back();
    }

    //
    //
    //  AJAX CONTROLLERS
    //
    //


    /**
     * Get variations for a product of given id
     *
     * @param $id.   Represents the product id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVariations($id){
        $product = Product::find($id);
        return Response::json($product->variations);
    }
}
