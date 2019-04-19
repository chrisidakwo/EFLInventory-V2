<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockBatchesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('stock_batches', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid("variation_id")->index("ix_stock_batches_variation_id");
            $table->float("ordered_quantity");
            $table->float("previous_inventory_quantity");
            $table->float("current_inventory_quantity");
            $table->float("unit_cost", 24);
            $table->float("retail_price", 24);
            $table->float("wholesale_price", 24);
            $table->boolean("on_sale")->default(false);
            $table->date("expiry_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table("stock_batches", static function (Blueprint $table) {
            $table->dropIndex("ix_stock_batches_variation_id");
            $table->drop();
        });
    }
}
