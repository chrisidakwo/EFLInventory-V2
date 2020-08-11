<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;

class SubCategoryController extends Controller {
    /**
     * SubCategoryController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function show(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $subcategories = SubCategory::query()->orderBy('name')->get();
            $categories = Category::query()->orderBy('name')->get();

            return view('inventory.subcategories', compact('subcategories', 'categories'));
        }

        abort(401, 'You do not have the required authorisation.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $this->validate($request, [
                'name' => 'required|unique:sub_categories|max:50',
            ]);

            $name = $request['name'];
            $category_id = $request['category_id'];

            SubCategory::addNewSubCategory($name, $category_id);

            Session::flash('success', 'SubCategory has been saved successfully');

            return redirect('/inventory/subcategories');
        }

        return redirect()->home();
    }

    public function edit(Request $request, $slug) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $name = $request['name'];
            $category_id = $request['category_id'];

            SubCategory::updateSubCategory($slug, $name, $category_id);
            session()->flash('success', 'Category has been updated successfully');

            return redirect()->route('showSubCategories');
        }

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @param $slug
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(Request $request, $slug): RedirectResponse {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $name = $request['name'];

            if (SubCategory::deleteSubCategory($slug)) {
                session()->flash('success', Str::title($name) . ' has been deleted successfully');

                return redirect()->route('showSubCategories');
            }

            session()->flash('error', Str::title($name) . ' cannot be deleted because it is associated with products!');

            return back();
        }

        return redirect()->home();
    }
}
