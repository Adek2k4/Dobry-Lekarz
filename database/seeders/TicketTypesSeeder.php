<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticketTypes = [
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
        ];

        foreach ($ticketTypes as $type) {
            DB::table('ticket_types')->insert([
                'description' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
