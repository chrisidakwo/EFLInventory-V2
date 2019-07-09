<?php

namespace EFLInventory\Http\Controllers\Common;

use EFLInventory\Models\Common\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use EFLInventory\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function index(Request $request) {
        $categories = Category::with("products.count")->paginate(15);
        return view("common.category.index", compact("categories"));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        $validator = Validator::make($request->input(), [
            "name" => ["required", "unique:categories"],
            "type" => ["required"]
        ], [
            "name.required" => "The category name is required!",
            "type.required" => "The category type is required"
        ]);

        $name = $request->get("name");
        $slug = Str::slug($name);
        $type = $request->get("type");
        $parentId = $request->get("parent") === "none" ? null : $request->get("parent");

        $validator->after(static function ($validator) use ($parentId) {
            if (!empty($parentId)) {
                $parentExists = Category::query()->where("parent_id", $parentId)->exists();
                if (!$parentExists) {
                    $validator->errors()->add("parent", "The selected parent category does not exist!");
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $category = Category::create([
            "name" => $name,
            "slug" => $slug,
            "type" => $type,
            "parent_id" => $parentId,
        ]);

        // TODO: Find a way to calculate depth
        Session::flash("success", "Category has been created!");
        return back();
    }

}
