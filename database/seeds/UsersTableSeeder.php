<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        \EFLInventory\Models\Auth\User::create([
            "name" => "EFL Administrator",
            "username" => "efl-admin",
            "password" => bcrypt("secret"),
            "is_admin" => true,
            "is_staff" => true
        ]);
    }
}
