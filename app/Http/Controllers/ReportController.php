<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ProductVariation;
use App\Models\PurchaseHistory;
use App\Models\SalesGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @return Application|Factory|View
     */
    public function getPurchaseTransactions() {
        $transactions = PurchaseHistory::all();
        $variations = ProductVariation::all();
        $batches = Batch::all();

        return view('reports.purchase', compact('transactions', 'variations', 'batches'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getSalesTransactions(Request $request) {
        $transactions = SalesGroup::all();

        return view('reports.sales', compact('transactions'));
    }
}
