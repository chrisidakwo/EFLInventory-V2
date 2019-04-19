<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('products', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string("name")->index("ix_products_name");
            $table->string("slug")->index("ix_products_slug");
            $table->string("category_id")->index("ix_products_category_id");
            $table->string("upc_code")->nullable();
            $table->string("brand_id")->nullable();
            $table->text('image_path')->nullable();
            $table->text('thumb_image_path')->nullable();
            $table->boolean("status")->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->unique("slug", "uix_products_slug");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table("products", static function (Blueprint $table) {
            $table->dropIndex("ix_products_name");
            $table->dropIndex("ix_products_slug");
            $table->dropIndex("ix_products_category_id");
            $table->dropUnique("uix_products_slug");
            $table->drop();
        });
    }
}
