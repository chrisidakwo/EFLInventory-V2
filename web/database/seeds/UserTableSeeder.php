<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $role_manager = Role::where('name', 'Manager')->first();

        $manager = new User();
        $manager->name = 'System Administrator';
        $manager->username = 'admin';
        $manager->password = bcrypt('password1234');
        $manager->save();
        $manager->roles()->attach($role_manager);
    }
}
