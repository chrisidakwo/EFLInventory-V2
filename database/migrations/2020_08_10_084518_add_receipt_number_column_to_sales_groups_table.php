<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReceiptNumberColumnToSalesGroupsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->string('receipt_no')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });
    }
}
