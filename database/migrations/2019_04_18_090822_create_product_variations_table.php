<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('product_variations', static function (Blueprint $table) {
            $table->uuid('variation_id')->primary();
            $table->string("generic_name");
            $table->string("slug")->index("ix_product_variations_slug");
            $table->integer("product_id")->unsigned()->index("ix_product_variations_product_id");
            $table->string("sku")->index("ix_product_variations_sku");
            $table->string("variation_type")->index("ix_product_variations_type");
            $table->double("variation_type_value")->nullable()->comment("Where if the variation_type is weight, then this will contain the weight value or size value or color value, as the case may be.");
            $table->double("variation_type_unit")->nullable()->comment("Where is the variation_type id weight, then this will contain say kg or mm or g or ltr or whatever be the unit for measuring the value of the variation type.");
            $table->integer("current_stock")->default(0);
            $table->integer("stock_threshold")->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->unique("slug", "uix_product_variations_slugs");
            $table->unique("sku", "uix_product_variations_sku");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table("product_variations", static function (Blueprint $table) {
            $table->dropIndex("ix_product_variations_slug");
            $table->dropIndex("ix_product_variations_product_id");
            $table->dropIndex("ix_product_variations_sku");
            $table->dropIndex("ix_product_variations_type");
            $table->dropUnique("uix_product_variations_slugs");
            $table->dropUnique("uix_product_variations_sku");
            $table->drop();
        });
    }
}
