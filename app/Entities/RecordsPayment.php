<?php

namespace EFLInventory\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class RecordsPayment extends Model {
    public const PAYMENT_METHOD_CASH = "cash";
    public const PAYMENT_METHOD_CARD = "card";
    public const PAYMENT_METHOD_BANK_CHEQUE = "bank_cheque";
    public const PAYMENT_METHOD_BANK_TRANSFER = "bank_transfer";
}
