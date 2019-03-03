<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name", 50)->unique()->index();
            $table->string('image_path', 500);
            $table->string('thumb_image_path', 500);
            $table->string('slug', 60)->unique()->index();
            $table->string("upc_code", 150)->unique()->index();
            $table->integer("category_id")->unsigned();
            $table->integer("subcategory_id")->unsigned();
            $table->integer("brand_id")->unsigned();
            $table->string("variate_by", 20);
            $table->boolean("status")->default(true);
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
        Schema::dropIfExists('products');
    }
}
