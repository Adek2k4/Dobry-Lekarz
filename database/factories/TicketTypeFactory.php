<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->randomElement([
                'Podszywanie się pod innego lekarza',
                'Fałszowanie kwalifikacji lub certyfikatów',
                'Nieuprawnione wykonywanie praktyki medycznej',
                'Żądanie nielegalnych opłat',
                'Molestowanie lub przemoc wobec pacjenta',
                'Naruszenie tajemnicy lekarskiej',
                'Przepisywanie leków bez uzasadnienia medycznego',
                'Wykonywanie zabiegów w stanie nietrzeźwości',
                'Oszustwa finansowe',
                'Nielegalna sprzedaż recept',
                'Inne poważne naruszenie',
            ]),
        ];
    }
}
