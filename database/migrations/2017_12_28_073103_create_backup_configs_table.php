<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackupConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backup_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("account", 550);
            $table->string("password", 2500);
            $table->string("account_key", 2500)->default("");
            $table->string("folder_name", 2500)->default("");
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
        Schema::dropIfExists('backup_configs');
    }
}
