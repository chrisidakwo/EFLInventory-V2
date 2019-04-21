<?php

use EFLInventory\Models\Auth\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void {
        User::create([
            "id" => \EFLInventory\Support\DB::generateId(User::class),
            "name" => "EFL Administrator",
            "username" => "efl-admin",
            "password" => bcrypt("secret"),
            "is_admin" => true,
            "is_staff" => true
        ]);
    }
}
