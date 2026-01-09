<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Kardiolog',
                'Chirurg',
                'Stomatolog',
                'Pediatra',
                'Dermatolog',
                'Ginekolog',
                'Endokrynolog',
                'Neurolog',
                'Ortopeda',
                'Urolog',
                'Okulista',
                'Laryngolog',
                'Psychiatra',
                'Reumatolog',
                'Pulmonolog',
            ]),
        ];
    }
}
