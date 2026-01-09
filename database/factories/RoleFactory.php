<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'patient', // Default to patient
        ];
    }
    
    public function patient(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'patient',
        ]);
    }
    
    public function doctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'doctor',
        ]);
    }
    
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
        ]);
    }
}
