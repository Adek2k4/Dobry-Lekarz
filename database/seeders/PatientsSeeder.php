<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pl_PL');

        $patientRole = Role::where('name', 'patient')->first();
        $roleId = $patientRole ? $patientRole->id : null;

        for ($i = 0; $i < 50; $i++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = $faker->unique()->safeEmail();

            User::create([
                'name' => $firstName,
                'surname' => $lastName,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => $roleId,
                'phone' => $faker->numerify('#########'),
            ]);
        }
    }
}
