<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DealerController extends Controller {

    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $dealers = Dealer::all()->sortBy("name");
            $brands = Brand::all()->sortBy("name");

            return view("products.dealers", compact("dealers", "brands"));
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    public function store(Request $request) {
        $this->validate($request, [
            "full_name" => "required|unique:dealers|max:50",
        ]);

        $values = array(
            "name" => $request["name"],
            "full_name" => $request["full_name"],
            "phone" => $request["phone"],
            "brands" => $request["brands"]
        );

        $dealer = new Dealer();
        if($dealer->AddNewDealer($values)) {
            \Session::flash("success", "Dealer has been saved successfully");
            return redirect()->route("showDealers");
        }
    }

    public function edit(Request $request, $id) {
        $values = array(
            "full_name" => $request["full_name"],
            "brands" => $request["brands"],
            "phone" => $request["phone"],
            "dealer_id" => $id
        );

        $dealer = new Dealer();
        if($dealer->UpdateDealer($values)) {
            \Session::flash("success", "Dealer has been updated successfully");
            return redirect()->route("showDealers");
        }
    }

    public function delete(Request $request, $id) {
        $full_name = $request["full_name"];
        $dealer = new Dealer();
        if($dealer->DeleteDealer($id)) {
            \Session::flash("success", Str::title($full_name) . " has been deleted successfully");
            return redirect()->route("showDealers");
        }
    }
}
