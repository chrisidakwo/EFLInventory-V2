<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTotalQuantityToSalesGroupsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->integer('total_quantity')->after('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->dropColumn('total_quantity');
        });
    }
}
