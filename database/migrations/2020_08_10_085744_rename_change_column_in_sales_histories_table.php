<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameChangeColumnInSalesHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sales_histories', static function (Blueprint $table) {
            $table->renameColumn('change', 'change_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sales_histories', static function (Blueprint $table) {
            $table->renameColumn('change_amount', 'change')->change();
        });
    }
}
