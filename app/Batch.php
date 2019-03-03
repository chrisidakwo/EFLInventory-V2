<?php

namespace App;

use ArrayObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Batch extends Model {

    /**
     * @param $batchId
     * @return bool|mixed
     */
    public function getById($batchId) {
        if($batchId > 0) {
            $batch = $this->all()->where("id", "=", $batchId)->first();
            return $batch;
        }

        return false;
    }

    /**
     * @param ProductVariation $id
     * @return Batch
     */
    public function activeBatch($id) {
        return Batch::where("variation_id", $id)->where("on_sale", 1)->first();
    }

    public function variation() {
        return $this->belongsTo(ProductVariation::class);
    }

    public function sales() {
        return $this->belongsToMany(SalesHistory::class);
    }

    /**
     * @param array $values
     * @return bool
     * @throws \Exception
     */
    public function AddNewBatch(array $values) {
        $uuid_code = Uuid::uuid4();

        $entry = new Batch();
        $entry->batch_code = $uuid_code;
        $entry->variation_id = $values["variation_id"];
        $entry->ordered_quantity = $values["quantity"];
        $entry->current_quantity = $values["quantity"];
//        $entry->total_cost = $values["total_cost"];
        $entry->unit_cost = $values["unit_cost"];
        $entry->retail_price = $values["retail_price"];
        $entry->wholesale_price = $values["wholesale_price"];
        $entry->expiry_date = Carbon::parse($values["expiry_date"]);
        $batch_status = new ArrayObject();
        $batches = Batch::all()->where("variation_id", "=", $values["variation_id"])->all();
        foreach($batches as $batch) {
            $batch_status->append($batch->on_sale);
        }

        $entry->on_sale = (in_array(1, $batch_status->getArrayCopy())) ? 0 : 1;

        // Update variation stock
        $variation = ProductVariation::find($values["variation_id"]);
        if($variation) {
            $variation->stock += $values["quantity"];
            $variation->save();

            // Associate relationships
            $entry->variation()->associate($variation);

            // Save stock entry
            $entry_saved = $entry->save();

            // Create new purchase history
            $batch = Batch::all()->where("batch_code", "=", $uuid_code)->first();
            $purchase_history = new PurchaseHistory();
            $purchase_history->variation_id = $values["variation_id"];
            $purchase_history->batch_id = $batch->id;
            $purchase_history->balance_due = $values["balance_due"];
            $purchase_history->change = $values["change"];
            $purchase_history->payment_method = $values["payment_method"];
            $purchase_history->remarks = $values["remarks"];

            // Associate relationships
            $purchase_history->variation()->associate($variation);

            // Save purchase history
            $history_saved = $purchase_history->save();

            if($entry_saved && $history_saved) {
                // Add action history
                $description = "Added a new batch/stock for product: " . $variation->variation_name;
                ActionHistory::AddNewActionHistory($description);

                return true;
            } else {
                try {
                    // This only happens if the batch didn't save in DB properly.
                    // The idea here is just to delete all related persisted data to avoid conflict
                    $entry->delete();
                    $purchase_history->delete();
                } catch (\Exception $ex) {

                }
            }

            return false;
        } else {
            return false;
        }
    }
}
