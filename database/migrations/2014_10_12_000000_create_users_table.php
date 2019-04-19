<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('users', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('username', 12)->index("ix_users_username");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false)->index();
            $table->string('is_staff')->default(true)->index();
            $table->dateTime('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique("username", "uix_users_username");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table("users", static function (Blueprint $table) {
            $table->dropIndex("ix_users_username");
            $table->dropUnique("uix_users_username");

            $table->drop();
        });
    }
}
