<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('brands', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string("name");
            $table->string("slug")->index("ix_brands_slug");
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
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
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex("ix_brands_slug");
            $table->drop();
        });
    }
}
