<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $patientRole = Role::firstOrCreate(['name' => 'patient']);

        // Create admin user
        $adminEmail = 'admin@example.com';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Ensure admin has role
        $admin->role_id = $adminRole->id;
        $admin->save();
    }
}
