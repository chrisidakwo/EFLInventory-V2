<?php

namespace EFLInventory\Http\Controllers\Inventory;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use EFLInventory\Http\Controllers\Controller;

class InventoryController extends Controller {

    public function index(Request $request): Renderable {
        return view("inventory.index");
    }
}
