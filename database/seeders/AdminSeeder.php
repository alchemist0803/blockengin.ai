<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create the admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Create a user and assign the admin role
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('blockbrowser123!@#'),
        ]);
        $user->assignRole($adminRole);
    }
}
