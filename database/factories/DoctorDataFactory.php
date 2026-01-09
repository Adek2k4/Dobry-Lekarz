<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Address;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorData>
 */
class DoctorDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'specialization_id' => Specialization::factory(),
            'description' => fake()->paragraph(),
            'profile_picture' => null,
        ];
    }
}
