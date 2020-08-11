<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Auth;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;

class CategoryController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function show() {
        if (Auth::user()->authorizeRoles(['Manager'])) {
            $categories = Category::query()->orderBy('name')->get();

            return view('inventory.categories', compact('categories'));
        }

        abort(401, 'You do not have the required authorisation.');

        return redirect('/login');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getSubCategories($id): JsonResponse {
        $subCategories = SubCategory::query()->where('category_id', $id)->get();

        return response()->json($subCategories);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Request $request) {
        if (Auth::user()->authorizeRoles(['Manager'])) {
            $this->validate($request, [
                'name' => 'required|unique:categories|max:50',
            ]);

            Category::addNewCategory($request->get('name'));

            Session::flash('success', 'Category has been saved successfully');

            return redirect('/inventory/categories');
        }

        abort(401, 'You do not have the required authorisation.');

        return redirect('/login');
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|RedirectResponse|Redirector
     */
    public function edit(Request $request, $slug) {
        if (Auth::user()->authorizeRoles(['Manager'])) {
            $name = $request['name'];
            if (Category::updateCategory($slug, $name)) {
                Session::flash('success', 'Category has been updated successfully');

                return redirect()->route('showCategories');
            }
        }

        abort(401, 'You do not have the required authorisation.');

        return redirect('/login');
    }

    /**
     * @param $request
     * @param $slug
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function delete($request, $slug) {
        if (Auth::user()->authorizeRoles(['Manager'])) {
            $name = $request['name'];
            if (Category::deleteCategory($slug)) {
                Session::flash('success', Str::title($name) . ' has been deleted successfully');

                return redirect()->route('showCategories');
            }

            session()->flash('error', Str::title($name) . ' cannot be deleted because it is associated with products!');

            return back();
        }

        abort(401, 'You do not have the required authorisation.');

        return redirect('/login');
    }
}
