<?php

namespace EFLInventory\Http\Controllers\Common;

use EFLInventory\Models\Common\Category;
use Illuminate\Http\Request;
use EFLInventory\Http\Controllers\Controller;

class CategoryController extends Controller {
    public function index(Request $request) {
        $categories = Category::with("products.count")->paginate(15);
        return view("common.category.index", compact("categories"));
    }
}
