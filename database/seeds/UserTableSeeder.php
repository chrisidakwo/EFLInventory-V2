<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = Role::where("name", "Employee")->first();
        $role_manager = Role::where("name", "Manager")->first();

        $employee = new User();
        $employee->name = "John Doe";
        $employee->username = "johndoe";
        $employee->password = bcrypt("secret");
        $employee->save();
        $employee->roles()->attach($role_employee);

        $manager = new User();
        $manager->name = "Chris Idakwo";
        $manager->username = "chrisidakwo";
        $manager->password = bcrypt("secret");
        $manager->save();
        $manager->roles()->attach($role_manager);
    }
}
