<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\ProductVariation;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;

class StockEntryController extends Controller {
    /**
     * StockEntryController constructor.
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
            $products = Product::all()->sortBy('name');

            return view('inventory.stock.entry', compact('products'));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $this->validate($request, [
                'product' => 'required',
                'prod-variation' => 'required',
                'quantity' => 'required',
                'total-cost' => 'required',
                'unit-cost' => 'required',
                'retail-price' => 'required',
                'expiry-date' => 'required'
            ]);

            $product = ProductVariation::find($request['prod-variation'])->variation_name;

            $diff = ((float) $request['total-cost']) - ((float) $request['amount-paid']);
            $balance_due = ($diff < 0) ? 0.00 : $diff;
            $change = ($diff < 0) ? -($diff) : 0.00;

            $values = [
                'product_id' => $request['product'],
                'variation_id' => $request['prod-variation'],
                'quantity' => $request['quantity'],
                'total_cost' => (float) $request->get('total-cost'),
                'amount_paid' => (float) $request->get('amount-paid'),
                'unit_cost' => (float) $request['unit-cost'],
                'retail_price' => (float) $request['retail-price'],
                'wholesale_price' => (float) $request['wholesale-price'],
                'expiry_date' => $request['expiry-date'],
                'balance_due' => $balance_due,
                'change' => $change,
                'payment_method' => $request['payment-method'],
                'remarks' => $request['remarks'] ?: Carbon::now()->toDateTimeString()
            ];

            if (Batch::addNewBatch($values)) {
                Session::flash('success', "Inventory stock has been updated for: {$product}");

                return redirect()->back();
            }
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    //	public function dummy(Request $request) {
//		if ($request->user()->authorizeRoles(['Manager'])) {
//		}
//
//		abort(401, 'You do not have the required authorization.');
//	}
}
