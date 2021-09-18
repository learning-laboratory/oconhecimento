<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'         => 'super admin',
            'email'        => 'superadmin@example.com',
            'password'     => bcrypt('12345678'),
            'is_suspended' => false
        ]);

        $role = Role::where('name','super administrador')->get()->first();
        $user->roles()->sync($role->id);
    }
}
