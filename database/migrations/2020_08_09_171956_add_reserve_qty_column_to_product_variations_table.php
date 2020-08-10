<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReserveQtyColumnToProductVariationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product_variations', static function (Blueprint $table) {
            $table->integer('reserved_qty')->after('stock_threshold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('product_variations', static function (Blueprint $table) {
            $table->dropColumn('reserved_qty');
        });
    }
}
