<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('sales', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid("variation_id")->index("ix_sales_variation_id");
            $table->string("batch_id")->index("ix_sales_batch_id");
            $table->string("pos_transaction_id")->index("ix_sales_pos_transaction_id");
            $table->float("quantity");
            $table->float("unit_cost", 24);
            $table->float("total_cost", 24);
            $table->float("balance_due", 24);
            $table->float("change", 24);
            $table->boolean("is_discounted");
            $table->float("discount_amount", 24);
            $table->float("profit", 24);
            $table->boolean("is_wholesale")->default(false);
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
        Schema::table("sales", static function (Blueprint $table) {
            $table->dropIndex("ix_sales_variation_id");
            $table->dropIndex("ix_sales_batch_id");
            $table->dropIndex("ix_sales_pos_transaction_id");
            $table->drop();
        });
    }
}
