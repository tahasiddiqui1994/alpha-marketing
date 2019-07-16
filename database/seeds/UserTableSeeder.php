<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $role_agent = Role::where('name', 'agent')->first();
        // $role_closer  = Role::where('name', 'closer')->first();

        // $agent = new User();
        // $agent->name = 'Agent Name';
        // $agent->password = bcrypt('123123');
        // $agent->showpass = '123123';
        // $agent->disabled = 0;
        // $agent->save();
        // $agent->roles()->attach($role_agent);

        // $closer = new User();
        // $closer->name = 'Closer Name';
        // $closer->password = bcrypt('123123');
        // $closer->showpass = '123123';
        // $closer->disabled = 0;
        // $closer->save();
        // $closer->roles()->attach($role_closer);
    }
}
