<?php

namespace App\Models;

use ArrayObject;
use Carbon\Carbon;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

/**
 * App\Batch.
 *
 * @property int $id
 * @property string $batch_code
 * @property int $variation_id
 * @property int $ordered_quantity
 * @property int $current_quantity
 * @property float $unit_cost
 * @property float $retail_price
 * @property float $wholesale_price
 * @property int $on_sale
 * @property string $expiry_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|SalesHistory[] $sales
 * @property-read int|null $sales_count
 * @property-read ProductVariation $variation
 * @method static Builder|Batch newModelQuery()
 * @method static Builder|Batch newQuery()
 * @method static Builder|Batch query()
 * @method static Builder|Batch whereBatchCode($value)
 * @method static Builder|Batch whereCreatedAt($value)
 * @method static Builder|Batch whereCurrentQuantity($value)
 * @method static Builder|Batch whereExpiryDate($value)
 * @method static Builder|Batch whereId($value)
 * @method static Builder|Batch whereOnSale($value)
 * @method static Builder|Batch whereOrderedQuantity($value)
 * @method static Builder|Batch whereRetailPrice($value)
 * @method static Builder|Batch whereUnitCost($value)
 * @method static Builder|Batch whereUpdatedAt($value)
 * @method static Builder|Batch whereVariationId($value)
 * @method static Builder|Batch whereWholesalePrice($value)
 * @mixin Eloquent
 */
class Batch extends Model {
    protected $fillable = ['id', 'batch_code', 'variation_id', 'ordered_quantity', 'current_quantity', 'unit_cost',
        'retail_price', 'wholesale_price', 'on_sale', 'on_sale', 'expiry_date'];

    /**
     * @param $batchId
     * @return bool|mixed
     */
    public function getById($batchId) {
        if ($batchId > 0) {
            return self::all()->where('id', '=', $batchId)->first();
        }

        return false;
    }

    /**
     * @param ProductVariation $id
     * @return Batch|Model
     */
    public function activeBatch($id) {
        return self::where('variation_id', $id)->where('on_sale', 1)->first();
    }

    /**
     * @param array $values
     * @return bool
     * @throws Exception
     */
    public static function addNewBatch(array $values): bool {
        $uuid_code = Uuid::uuid4();

        $entry = new static;
        $entry->batch_code = $uuid_code;
        $entry->variation_id = $values['variation_id'];
        $entry->ordered_quantity = $values['quantity'];
        $entry->current_quantity = $values['quantity'];
//        $entry->total_cost = $values["total_cost"];
        $entry->unit_cost = $values['unit_cost'];
        $entry->retail_price = $values['retail_price'];
        $entry->wholesale_price = $values['wholesale_price'];
        $entry->expiry_date = Carbon::parse($values['expiry_date']);
        $batch_status = new ArrayObject();

        $batches = self::where('variation_id', '=', $values['variation_id'])->get();
        foreach ($batches as $batch) {
            $batch_status->append($batch->on_sale);
        }

        $entry->on_sale = (in_array(1, $batch_status->getArrayCopy(), true)) ? 0 : 1;

        // Update variation stock
        $variation = ProductVariation::find($values['variation_id']);
        if ($variation) {
            $variation->stock += $values['quantity'];
            $variation->save();

            // Associate relationships
            $entry->variation()->associate($variation);

            // Save stock entry
            $entry_saved = $entry->save();

            // Create new purchase history
            $batch = self::all()->where('batch_code', '=', $uuid_code)->first();
            $purchase_history = new PurchaseHistory();
            $purchase_history->variation_id = $values['variation_id'];
            $purchase_history->batch_id = $batch->id;
            $purchase_history->total_amount = (float) $values['total_cost'];
            $purchase_history->amount_paid = (float) $values['amount_paid'];
            $purchase_history->balance_due = $values['balance_due'];
            $purchase_history->change = $values['change'];
            $purchase_history->payment_method = $values['payment_method'];
            $purchase_history->remarks = $values['remarks'];

            // Associate relationships
            $purchase_history->variation()->associate($variation);

            // Save purchase history
            $history_saved = $purchase_history->save();

            if ($entry_saved && $history_saved) {
                // Add action history
                $description = 'Added a new batch/stock for product: ' . $variation->variation_name;
                ActionHistory::AddNewActionHistory($description);

                return true;
            }

            try {
                // This only happens if the batch didn't save in DB properly.
                // The idea here is just to delete all related persisted data to avoid conflict
                $entry->delete();
                $purchase_history->delete();
            } catch (Exception $ex) {
                // Nothing here
            }

            return false;
        }

        return false;
    }

    /**
     * @return BelongsTo
     */
    public function variation(): BelongsTo {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * @return BelongsToMany
     */
    public function sales(): BelongsToMany {
        return $this->belongsToMany(SalesHistory::class);
    }
}
