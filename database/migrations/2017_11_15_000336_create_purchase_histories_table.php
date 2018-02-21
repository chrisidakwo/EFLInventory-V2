<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("variation_id")->unsigned();
            $table->integer("batch_id")->unsigned();
            $table->float("balance_due", 12, 2);
            $table->float("change", 12, 2);
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
        Schema::dropIfExists('purchase_histories');
    }
}
