<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles, admin and specializations
        $this->call([
            \Database\Seeders\AdminSeeder::class,
            \Database\Seeders\SpecializationsSeeder::class,
            \Database\Seeders\TicketTypesSeeder::class,
            \Database\Seeders\DoctorsSeeder::class,
            \Database\Seeders\PatientsSeeder::class,
            \Database\Seeders\AppointmentsSeeder::class,
            \Database\Seeders\ReviewsSeeder::class,
            \Database\Seeders\TicketsSeeder::class,
        ]);
    }
}
