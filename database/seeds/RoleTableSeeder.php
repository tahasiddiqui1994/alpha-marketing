<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $role_employee = new Role();
        $role_employee->name = 'agent';
        $role_employee->description = 'An Agent User';
        $role_employee->save();
        $role_manager = new Role();
        $role_manager->name = 'closer';
        $role_manager->description = 'A Closer User';
        $role_manager->save();
    }
}
