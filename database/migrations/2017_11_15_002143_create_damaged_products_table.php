<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamagedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damaged_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("variation_id")->unsigned();
            $table->integer("quantity");
            $table->string("damage_cause", 250);
            $table->string("description", 1000);
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
        Schema::dropIfExists('damaged_products');
    }
}
