<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\POSProduct;
use Illuminate\Http\Request;

class POSController extends Controller {
    public function __construct(Request $request) {
        $this->middleware('auth');
    }

    public function show() {
        $categories = Category::all()->sortBy('name');
        $products = new POSProduct();
        $products = $products->all();

        return view('pos.show', compact('categories', 'products'));
    }

    //	public function getPOSProduct($id) {
//		if ($id !== 0) {
//			$product = new POSProduct();
//			$product = $product->findByVariation($id);
//			$product = new ArrayObject($product);
//
//			return Response::json($product);
//		}
//
//		$products = new POSProduct();
//
//		return $products->all();
//	}

//	public function getProductsByCategory($category_id): JsonResponse {
//		$category = Category::find($category_id);
//		$products = new POSProduct();
//		$products = $products->all();
//		$result = [];
//		foreach ($products as $product) {
//			if ($product->category == $category->name) {
//				Arr::add($result, $category->id, $category);
//			}
//		}
//
//		return Response::json([$products]);
//	}
}
