<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string("name");
            $table->string("phone");
            $table->softDeletes();
            $table->timestamps();

            $table->unique("phone", "uix_suppliers_phone");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique("uix_suppliers_phone");
            $table->drop();
        });
    }
}
