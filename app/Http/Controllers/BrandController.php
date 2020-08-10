<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

class BrandController extends Controller {
    /**
     * BrandController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function show(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $brands = Brand::all()->sortBy('name');

            return view('products.brands', compact('brands'));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect('/login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $this->validate($request, [
            'name' => 'required|unique:brands|max:50',
        ]);

        $values = [
            'name' => $request['name'],
            'contact' => $request['contact'],
            'address' => $request['address']
        ];

        if (Brand::addNewBrand($values)) {
            Session::flash('success', 'Brand has been saved successfully');

            return redirect()->route('showBrands');
        }

        session()->flash('error', 'Could not add brand!');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|RedirectResponse|Redirector
     */
    public function edit(Request $request, $slug) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $values = [
                'name' => $request['name'],
                'contact' => $request['contact'],
                'address' => $request['address'],
                'slug' => $slug
            ];

            if (Brand::updateBrand($values)) {
                Session::flash('success', 'Brand has been updated successfully');

                return redirect()->route('showBrands');
            }
        }

        return redirect('/login');
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function delete(Request $request, $slug) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $name = $request['name'];
            if (Brand::deleteBrand($slug)) {
                Session::flash('success', Str::title($name) . ' has been deleted successfully');

                return redirect()->route('showBrands');
            }

            session()->flash('error', Str::title($name) . ' cannot be deleted because it is associated with products!');

            return back();
        }

        abort(401, 'You do not have the required authorization!');

        return redirect('/login');
    }
}
