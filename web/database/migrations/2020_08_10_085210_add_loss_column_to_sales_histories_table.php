<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLossColumnToSalesHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sales_histories', static function (Blueprint $table) {
            $table->float('loss', 12, 2)->default(0.00)->after('profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sales_histories', static function (Blueprint $table) {
            $table->dropColumn('loss');
        });
    }
}
