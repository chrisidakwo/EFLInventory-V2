<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Product;
use App\ProductVariation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockEntryController extends Controller {
    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $products = Product::all()->sortBy("name");
            return view("inventory.stock.entry", compact("products"));
        }

        abort(401, "You do not have the required authorization.");

    }

    public function store(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $this->validate($request, array(
                "product" => "required",
                "prod-variation" => "required",
                "quantity" => "required",
                "total-cost" => "required",
                "unit-cost" => "required",
                "retail-price" => "required",
                "expiry-date" => "required"
            ));

            $entry = new Batch();

            $product = ProductVariation::find($request["prod-variation"])->variation_name;

            $diff = $request["total-cost"] - $request["amount-paid"];
            $balance_due = ($diff < 0) ? 0.00 : $diff;
            $change = ($diff < 0) ? -($diff) : 0.00;

            $values = [
                "product_id" => $request["product"],
                "variation_id" => $request["prod-variation"],
                "quantity" => $request["quantity"],
                "total_cost" => $request["total-cost"],
                "unit_cost" => $request["unit-cost"],
                "retail_price" => $request["retail-price"],
                "wholesale_price" => $request["wholesale-price"],
                "expiry_date" => $request["expiry-date"],
                "balance_due" => $balance_due,
                "change" => $change,
                "payment_method" => $request["payment-method"],
                "remarks" => $request["remarks"] ?: Carbon::now()
            ];

            if($entry->AddNewBatch($values)){
                \Session::flash("success", "Inventory stock has been updated for: {$product}");
                return redirect()->back();
            }
        }

        abort(401, "You do not have the required authorization.");
    }

    public function dummy(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {

        }

        abort(401, "You do not have the required authorization.");
    }
}
