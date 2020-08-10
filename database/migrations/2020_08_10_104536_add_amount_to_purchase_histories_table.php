<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAmountToPurchaseHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('purchase_histories', static function (Blueprint $table) {
            $table->float('amount', 12, 2)->after('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('purchase_histories', static function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}
