<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAndAlterColumnsInSalesGroupsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->renameColumn('change', 'change_amount')->change();
            $table->string('seller')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sales_groups', static function (Blueprint $table) {
            $table->renameColumn('change_amount', 'change')->change();
            $table->dropColumn('seller');
        });
    }
}
