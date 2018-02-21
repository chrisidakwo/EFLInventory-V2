<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = "Employee";
        $role_employee->description = "An employee user";
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = "Manager";
        $role_manager->description = "Store Manager";
        $role_manager->save();
    }
}
