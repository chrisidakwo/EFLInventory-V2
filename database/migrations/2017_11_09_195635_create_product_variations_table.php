<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->increments('id');
            $table->string("variation_name", 100);
            $table->string("image_path", 1000);
            $table->string("thumb_image_path", 1000);
            $table->string('slug', 60);
            $table->integer("product_id")->unsigned();
            $table->string("sku", 20);
            $table->double("weight")->nullable();
            $table->string("weight_unit")->nullable();
            $table->string("color", 10)->nullable();
            $table->string("size", 10)->nullable();
            $table->integer("stock")->default(0);
            $table->integer("stock_threshold")->default(0);
            $table->timestamps();

            $table->index("sku", "IX_ProductVariations_SKU");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
