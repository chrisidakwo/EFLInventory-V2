<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->text("products");
            $table->string("total_amount", 250);
            $table->string("amount_tendered", 250);
            $table->string("change", 250);
            $table->string("balance_due", 250);
            $table->string("payment_method", 250);
            $table->text("remarks");
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
        Schema::dropIfExists('sales_groups');
    }
}
