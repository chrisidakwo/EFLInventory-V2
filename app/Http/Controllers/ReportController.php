<?php

namespace App\Http\Controllers;

use App\Batch;
use App\ProductVariation;
use App\PurchaseHistory;
use App\SalesGroup;
use App\SalesHistory;
use Illuminate\Http\Request;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware("auth");
    }

    public function getPurchaseTransactions() {
        $transactions = PurchaseHistory::all();
        $variations = ProductVariation::all();
        $batches = Batch::all();
        return view("reports.purchase", compact("transactions", "variations", "batches"));
    }

    public function getSalesTransactions(Request $request) {
        $transactions = SalesGroup::all();
        return view("reports.sales", compact("transactions"));
    }
}
