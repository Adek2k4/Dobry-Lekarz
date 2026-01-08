<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\DoctorData;
use App\Models\OfficeHour;
use App\Models\Role;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pl_PL');

        $doctorRole = Role::where('name', 'doctor')->first();
        $roleId = $doctorRole ? $doctorRole->id : null;

        $specializations = Specialization::all();

        for ($i = 0; $i < 100; $i++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = $faker->unique()->safeEmail();

            $user = User::create([
                'name' => $firstName,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => $roleId,
                'phone' => $faker->numerify('#########'),
                'surname' => $lastName,
            ]);

            $address = Address::create([
                'city' => $faker->city(),
                'postal_code' => $faker->numerify('##-###'),
                'street' => $faker->streetName(),
                'house_number' => $faker->buildingNumber(),
            ]);

            $special = $specializations->random()?->id;

            DoctorData::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'specialization_id' => $special,
                'description' => $faker->paragraph(),
            ]);

            // Create office hours (3-6 random days per week)
            $numberOfDays = $faker->numberBetween(3, 6);
            $selectedDays = $faker->randomElements([1, 2, 3, 4, 5, 6, 7], $numberOfDays);
            
            foreach ($selectedDays as $dayOfWeek) {
                // Generate realistic work hours
                $startHour = $faker->numberBetween(7, 10);
                $endHour = $faker->numberBetween($startHour + 4, 18);
                
                OfficeHour::create([
                    'doctor_id' => $user->id,
                    'day_of_week' => $dayOfWeek,
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                ]);
            }
        }
    }
}
