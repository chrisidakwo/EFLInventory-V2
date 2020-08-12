<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\POSProduct;
use App\Models\ProductVariation;
use App\Models\SalesGroup;
use App\Models\SalesHistory;
use Auth;
use Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use NumberFormatter;
use Response;

class CartController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     ** return all items in cart.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse {
        if (request()->ajax()) {
            return Response::json([Cart::getContent()]);
        }

        return Response::json([]);
    }

    /**
     ** add a new item to cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse {
        $batch_id = $request['batch_id'];
        $name = $request['name'];
        $price = $request['price'];
        $qty = $request['quantity'];


        // Before adding, check to ensure the current batch for product has enough quantity
        $product_batch = Batch::find($batch_id);
        $product_variation = ProductVariation::find($product_batch->variation_id);

        $item = Cart::get($batch_id);
        $item_total_qty = $qty;
        if ($item) {
            $item_total_qty += $item->quantity;
        }

        $remaining_stock = ($product_batch->current_quantity - $item_total_qty);

        $new_batch_exists = 'regular';
        $new_batch = null;

        if ($remaining_stock <= 0) {
            // set current batch "on_sale" value to 0
            $product_batch->on_sale = 0;
            $product_batch->save();
        }

        //if ($remaining_stock === -1) {
        if ($remaining_stock <= -1) {
            // check to see if a new batch exists
            /** @var Batch $new_batch */
            $new_batch = Batch::where('variation_id', $product_variation->id)->where('current_quantity', '>', 0)->first();

            // if it does, set the 'on_sale' value to 1
            if ($new_batch) {
                $new_batch->on_sale = 1;
                $new_batch->save();
                $new_batch_exists = 'true';
            } else {
                $new_batch_exists = 'false';
            }
        }

        // Note that the new batch is not what will be added to the cart at this time.
        // Not until the user requests for an additional quantity

        // Remaining quantity is more than zero
        $cart = Cart::add($product_batch->id, $name, $price, $qty, []);

        return Response::json([
            'new_batch' => $new_batch,
            'new_batch_exists' => $new_batch_exists,
            'cart' => $cart->getContent()->toArray()
            /*"item" => $item,
            "qty" => $qty,
            "remaining" => $remaining_stock*/
        ]);
    }

    /**
     ** update an existing cart item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse {
        $batch_id = $request['batch_id'];
        $price = $request['price'];
        $qty = $request['quantity'];

        Cart::update($batch_id, [
            'quantity' => [
                'relative' => (int) $qty['relative'],
                'value' => $qty['value']
            ],
            'price' => $price
        ]);

        return Response::json([Cart::getContent()]);
    }

    /**
     * remove an item from cart.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse {
        if ((int) $id > 0) {
            if (Cart::remove($id)) {
                return Response::json(['success' => 'Item has been removed from cart!']);
            }

            return Response::json(['error' => 'Item does not exist in cart!']);
        }

        return Response::json(['error' => 'Item id should be of type int and greater than zero!'], 406);
    }

    /**
     ** delete all items in cart.
     *
     * @return JsonResponse
     */
    public function deleteAll(): JsonResponse {
        $this->clearCart();

        return Response::json(['success' => 'All cart items have been removed!']);
    }

    /**
     ** clear all items in cart.
     */
    public function clearCart(): void {
        $items = Cart::getContent();
        
        foreach ($items as $item) {
            Cart::remove($item->id);
        }

        Cart::clear();
    }

    /**
     ** record sales of items in cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sell(Request $request): JsonResponse {
        $tendered_amount = $request['tendered'];
        $change = $request['change'];
        $remaining_balance = $request['balance_remaining'];
        $payment_method = title_case($request['payment_method']);
        $remarks = $request['remarks'];

        $sales_group = null;

        // All items in cart
        $items = Cart::getContent();

        // Get a comma-separated string of names of all products in cart
        $product_names = '';
        foreach ($items as $item) {
            $quantity = title_case((new NumberFormatter('en', NumberFormatter::SPELLOUT))
                ->format($item->quantity));

            $product_names .= "{$quantity} {$item->name}, ";
        }

        $last_receipt_no = 100;
        /** @var SalesGroup $_last_sale */
        $_last_sale = SalesGroup::query()->orderByDesc('id')->first();
        if ($_last_sale->exists()) {
            $last_receipt_no = $_last_sale->receipt_no;
        }

        $receiptNumber = str_pad($last_receipt_no + 1, 8, '0', STR_PAD_LEFT);

        // Create a new sales group
        $sales_group = SalesGroup::create([
            'products' => rtrim($product_names, ', '),
            'total_amount' => Cart::getSubTotal(),
            'total_quantity' => Cart::getTotalQuantity(),
            'amount_tendered' => $tendered_amount,
            'change_amount' => $change,
            'balance_due' => $remaining_balance,
            'payment_method' => $payment_method,
            'remarks' => empty($remarks) ? now(config('app.timezone'))->toDateTimeString() : $remarks,
            'seller' => Auth::user()->name,
            'receipt_no' => $receiptNumber
        ]);

        // Loop through each cart item
        // retrieve product and batch details
        // persist a new SalesHistory to DB
        // update each batch current_quantity value
        // update product stock value
        foreach ($items as $item) {
            //get active product batch
            $batch_id = $item->id;
            $active_batch = Batch::find($batch_id);

            // Get product variation
            $product_variation = ProductVariation::find($active_batch->variation_id);

            // Initialize helper variables
            //$itemUnitCost = $item->price;
            $itemGrossCost = $item->price * $item->quantity;
            $loss = 0.00;
            $profit = 0.00;

            // Calculate profit & loss
            if (($item->price - $active_batch->unit_cost) > 0) {
                $profit = ($item->price - $active_batch->unit_cost) * $item->quantity;
            } elseif (($item->price - $active_batch->unit_cost) < 0) {
                $loss = -1 * ($item->price - $active_batch->unit_cost) * $item->quantity;
            }

            // Create new selling history
            SalesHistory::create([
                'sales_group_id' => $sales_group->id,
                'variation_id' => $product_variation->id,
                'batch_id' => $active_batch->id,
                'quantity' => $item->quantity,
                'total_cost' => $itemGrossCost,
                'unit_cost' => $item->price,
                'profit' => $profit,
                'loss' => $loss,
                'payment_method' => $sales_group->payment_method
            ]);

            // reduce batch's current quantity by the sold quantity
            $active_batch->current_quantity -= $item->quantity;
            $active_batch->save();

            // update product variation stock value
            $product_variation->stock -= $item->quantity;
            $product_variation->save();
        }

        // return receipt number and app settings
        return Response::json([
            'sales_group' => $sales_group,
            'items' => $items,
            'settings' => [
                'contact' => config('org.contact'),
                'address' => config('org.address'),
                'name' => config('org.name')
            ]
        ]);
    }

    /**
     ** validate all items in cart
     ** ensure requested quantity is not more than available stock.
     *
     * @return JsonResponse
     */
    public function process(): JsonResponse {
        $error = '';

        $total = Cart::getTotal();
        if (((int) $total) === 0) {
            $error = 'The cart seems to be empty.';

            return Response::json(['error' => $error], 406);
        }

        $items = Cart::getContent();
        $pos_product = new POSProduct();
        foreach ($items as $item) {
            $product = $pos_product->findByBatch($item->id);
            if (((float) $item->quantity) > ((float) $product->available_stock)) {
                $error .= "Requested quantity for {$product->variation_name} is more than available stock.\n";
            }
        }

        if ($error !== '') {
            return Response::json(['error' => $error], 406);
        }

        return Response::json([
            'success' => 'All cart items can be sold!',
            'items' => $items,
        ]);
    }

//    /**
//     * @param $product_id
//     * @return Batch|Model
//     */
//    public function getProductBatch($product_id): Batch {
//        $batch = Batch::where('variation_id', $product_id)->where('on_sale', 1)->first();
//
//        return $batch ?? new Batch();
//    }
}
