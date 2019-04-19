<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('purchases', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid("variation_id")->index("ix_purchases_variation_id");
            $table->string("batch_id")->index("ix_purchases_batch_id");
            $table->float("balance_due", 24);
            $table->float("change", 12);
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
        Schema::table("purchases", static function (Blueprint $table) {
            $table->dropIndex("ix_purchases_variation_id");
            $table->dropIndex("ix_purchases_batch_id");
            $table->dropIndex("ix_purchases_payment_method");
            $table->drop();
        });
    }
}
