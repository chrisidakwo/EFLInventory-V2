<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterColumnsInPurchaseHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('purchase_histories', static function (Blueprint $table) {
            $table->renameColumn('amount', 'total_amount')->change();
            $table->float('amount_paid', 12, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('purchase_histories', static function (Blueprint $table) {
            $table->renameColumn('total_amount', 'amount')->change();
            $table->dropColumn('amount_paid');
        });
    }
}
