<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::create('inventory_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("month");
            $table->integer("year");
            $table->integer("total_stock_items");
            $table->float("total_stock_value");
            $table->integer("total_damaged_items");
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
        //
    }
}
