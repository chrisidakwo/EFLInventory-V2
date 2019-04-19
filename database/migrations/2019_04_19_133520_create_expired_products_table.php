<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpiredProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('expired_products', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid("variation_id")->index("ix_expired_products_variation_id");
            $table->string("batch_id")->index("ix_expired_products_batch_id");
            $table->float("quantity")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table("expired_products", static function (Blueprint $table) {
            $table->dropIndex("ix_expired_products_variation_id");
            $table->dropIndex("ix_expired_products_batch_id");
            $table->drop();
        });

        Schema::dropIfExists('expired_products');
    }
}
