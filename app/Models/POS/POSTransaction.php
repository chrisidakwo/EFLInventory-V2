<?php

namespace EFLInventory\Models\POS;

use EFLInventory\Entities\RecordsPayment;
use Illuminate\Database\Eloquent\SoftDeletes;

class POSTransaction extends RecordsPayment {
    use SoftDeletes;

    protected $table = "pos_transactions";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];
    protected $fillable = ["products", "total_amount", "amount_tendered", "change", "balance_due", "payment_method",
        "reference", "remark"];
}
