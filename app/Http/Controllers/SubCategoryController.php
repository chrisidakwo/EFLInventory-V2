<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;

class SubCategoryController extends Controller {

    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $subcategories = new SubCategory();
            $subcategories = $subcategories->getAll()->sortBy("name");
            $categories = Category::all()->sortBy("name");
            return view("inventory.subcategories", compact("subcategories", "categories"));
        }

        abort(401, "You do not have the required authorisation.");
    }

    public function store(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $this->validate($request, [
                "name" => "required|unique:sub_categories|max:50",
            ]);

            $name = $request["name"];
            $category_id = $request["category_id"];
            $subcategories = new SubCategory();
            if ($subcategories->AddNewSubCategory($name, $category_id)) {
                Session::flash("success", "SubCategory has been saved successfully");
                return redirect("/inventory/subcategories");
            }
        }

        redirect()->home();
    }

    public function edit(Request $request, $slug) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $name = $request["name"];
            $category_id = $request["category_id"];
            $subcategories = new SubCategory();
            if ($subcategories->UpdateSubCategory($slug, $name, $category_id)) {
                Session::flash("success", "Category has been updated successfully");
                return redirect()->route("showSubCategories");
            }
        }

        abort(401, "You do not have the required authorisation.");
    }

    public function delete(Request $request, $slug) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $name = $request["name"];
            $subcategories = new SubCategory();
            if ($subcategories->DeleteSubCategory($slug)) {
                Session::flash("success", Str::title($name) . " has been deleted successfully");
                return redirect()->route("showSubCategories");
            } else {
                Session::flash("error", Str::title($name) . " cannot be deleted because it is associated with products!");
                return back();
            }

        }

        redirect()->home();
    }
}
