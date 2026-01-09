<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => User::factory(),
            'patient_id' => User::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'reason' => fake()->sentence(),
        ];
    }
}
