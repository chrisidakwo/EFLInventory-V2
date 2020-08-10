<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Dealer;
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

class DealerController extends Controller {
    /**
     * DealerController constructor.
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
            $dealers = Dealer::query()->orderBy('name')->get();
            $brands = Brand::query()->orderBy('name')->get();

            return view('products.dealers', compact('dealers', 'brands'));
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
            'full_name' => 'required|unique:dealers|max:50',
        ]);

        $values = [
            'name' => $request['name'],
            'full_name' => $request['full_name'],
            'phone' => $request['phone'],
            'brands' => $request['brands']
        ];

        Dealer::addNewDealer($values);

        Session::flash('success', 'Dealer has been saved successfully');

        return redirect()->route('showDealers');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function edit(Request $request, $id): RedirectResponse {
        $values = [
            'full_name' => $request['full_name'],
            'brands' => $request['brands'],
            'phone' => $request['phone'],
            'dealer_id' => $id
        ];

        Dealer::updateDealer($values);

        Session::flash('success', 'Dealer has been updated successfully');

        return redirect()->route('showDealers');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(Request $request, $id): RedirectResponse {
        $full_name = $request['full_name'];

        Dealer::deleteDealer($id);

        Session::flash('success', Str::title($full_name) . ' has been deleted successfully');

        return redirect()->route('showDealers');
    }
}
