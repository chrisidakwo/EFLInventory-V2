<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('categories', function (Blueprint $table) {
            $table->string("id", 8)->primary();
            $table->string("parent_id")->index("ix_categories_parent_id")->nullable();
            $table->string("name");
            $table->string("slug")->index("ix_categories_slug");
            $table->enum("type", ["inventory", "purchases", "sales"])->index("ix_categories_type")->default("inventory");

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table("categories", function (Blueprint $table) {
            $table->dropIndex("ix_categories_parent_id");
            $table->dropIndex("ix_categories_slug");
            $table->dropIndex("ix_categories_type");
            $table->dropIfExists();
        });
    }
}
