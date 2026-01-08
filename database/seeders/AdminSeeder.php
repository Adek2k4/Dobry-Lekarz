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
        $adminEmail = 'a@a.a';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'surname' => 'Administrator',
                'password' => Hash::make('qwerasdf'),
                'role_id' => $adminRole->id,
            ]
        );

        // Ensure admin has role
        $admin->role_id = $adminRole->id;
        $admin->save();
    }
}
