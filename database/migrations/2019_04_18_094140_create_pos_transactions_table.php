<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('pos_transactions', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json("products");
            $table->float("total_amount", 24);
            $table->float("amount_tendered", 24);
            $table->float("change", 24);
            $table->float("balance_due", 24);
            $table->string("payment_method")->index("ix_purchases_payment_method");
            $table->string("reference")->nullable()->comment("Used when payment method is either cheque or bank transfer. If cheque, the cheque number is recorded. If bank transfer, the transaction reference is recorded.");
            $table->string("remark")->nullable()->comment("If cheque payment or bank transfer is used, you can record the name of the bank, account, and the account number.");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('pos_transactions');
    }
}
