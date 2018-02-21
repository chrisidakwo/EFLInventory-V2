<?php

namespace App\Http\Controllers;

use App\Brand;
use App\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        try {
            if ($request->user()->authorizeRoles(["Manager"])) {
                $brands = Brand::all()->sortBy("name");
                return view("products.brands", compact("brands"));
            }

            abort(401, "You do not have the required authorization.");
        } catch (\Exception $ex) {
            $inner_exception = $ex;
            $stack_trace = $ex->getTraceAsString();
            $error_message = $ex->getMessage();
            ErrorLog::AddNewError($inner_exception, $error_message, $stack_trace);
            return redirect("/login");
        }
    }

    public function store(Request $request) {
        $this->validate($request, [
            "name" => "required|unique:brands|max:50",
        ]);

        $values = array(
            "name" => $request["name"],
            "contact" => $request["contact"],
            "address" => $request["address"]
        );

        $brand = new Brand();
        if($brand->AddNewBrand($values)) {
            \Session::flash("success", "Brand has been saved successfully");
            return redirect()->route("showBrands");
        }
    }

    public function edit(Request $request, $slug) {
        try {
            if ($request->user()->authorizeRoles(["Manager"])) {
                $values = array(
                    "name" => $request["name"],
                    "contact" => $request["contact"],
                    "address" => $request["address"],
                    "slug" => $slug
                );

                $brand = new Brand();
                if ($brand->UpdateBrand($values)) {
                    \Session::flash("success", "Brand has been updated successfully");
                    return redirect()->route("showBrands");
                }
            }

            abort(401, "You do not have the required authorization");
        } catch (\Exception $ex) {
            $inner_exception = $ex;
            $stack_trace = $ex->getTraceAsString();
            $error_message = $ex->getMessage();
            ErrorLog::AddNewError($inner_exception, $error_message, $stack_trace);
            return redirect("/login");
        }
    }

    public function delete(Request $request, $slug) {
        try {
            if ($request->user()->authorizeRoles(["Manager"])) {
                $name = $request["name"];
                $brand = new Brand();
                if ($brand->DeleteBrand($slug)) {
                    \Session::flash("success", Str::title($name) . " has been deleted successfully");
                    return redirect()->route("showBrands");
                } else {
                    \Session::flash("error", Str::title($name) . " cannot be deleted because it is associated with products!");
                    return back();
                }
            }
        } catch (\Exception $ex) {
            $inner_exception = $ex;
            $stack_trace = $ex->getTraceAsString();
            $error_message = $ex->getMessage();
            ErrorLog::AddNewError($inner_exception, $error_message, $stack_trace);
            return redirect("/login");
        }

        abort(401, "You do not have the required authorization!");
    }
}
