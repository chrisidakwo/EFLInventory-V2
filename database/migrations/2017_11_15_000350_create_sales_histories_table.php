<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("variation_id")->unsigned();
            $table->integer("batch_id")->unsigned();
            $table->integer("sales_group_id")->unsigned();
            $table->integer("quantity")->unsigned();
            $table->float("unit_cost", 12, 2);
            $table->float("total_cost", 12, 2);
            $table->float("balance_due", 12, 2);
            $table->float("change", 12, 2);
            $table->boolean("is_discounted");
            $table->float("discount_amount", 12, 2);
            $table->float("profit", 12, 2);
            $table->boolean("is_wholesale")->default(false);
            $table->string("payment_method");
            $table->string("remarks");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_histories');
    }
}
