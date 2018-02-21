<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Session;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware("auth");
    }

    public function show() {
        if(Auth::user()->authorizeRoles(["Manager"])) {
            $categories = new Category();
            $categories = $categories->getAll()->sortBy("name");
            return view("inventory.categories", compact("categories"));
        }

        abort(401, "You do not have the required authorisation.");
    }

    public function getSubCategories($id) {
        $subCategories = new SubCategory();
        $subCategories = $subCategories->all()->where("category_id", $id);

        return Response::json($subCategories);
    }

    public function store(Request $request) {
        if(Auth::user()->authorizeRoles(["Manager"])) {
            $this->validate($request, [
                "name" => "required|unique:categories|max:50",
            ]);

            $name = $request["name"];
            $category = new Category();
            if ($category->AddNewCategory($name)) {
                Session::flash("success", "Category has been saved successfully");
                return redirect("/inventory/categories");
            }
        }

        abort(401, "You do not have the required authorisation.");
    }

    public function edit(Request $request, $slug) {
        if(Auth::user()->authorizeRoles(["Manager"])) {
            $name = $request["name"];
            $category = new Category();
            if ($category->UpdateCategory($slug, $name)) {
                Session::flash("success", "Category has been updated successfully");
                return redirect()->route("showCategories");
            }
        }

        abort(401, "You do not have the required authorisation.");
    }

    public function delete(Request $request, $slug) {
        if(Auth::user()->authorizeRoles(["Manager"])) {
            $name = $request["name"];
            $category = new Category();
            if ($category->DeleteCategory($slug)) {
                Session::flash("success", Str::title($name) . " has been deleted successfully");
                return redirect()->route("showCategories");
            } else {
                Session::flash("error", Str::title($name) . " cannot be deleted because it is associated with products!");
                return back();
            }
        }

        abort(401, "You do not have the required authorisation.");
    }

}
