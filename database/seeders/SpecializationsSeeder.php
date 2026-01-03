<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationsSeeder extends Seeder
{
    public function run()
    {
        $items = [
            'Kardiolog', 'Chirurg', 'Stomatolog', 'Pediatra', 'Dermatolog',
            'Ginekolog', 'Endokrynolog', 'Neurolog', 'Ortopeda', 'Urolog',
            'Okulista', 'Laryngolog', 'Psychiatra', 'Reumatolog', 'Pulmonolog',
        ];

        foreach ($items as $name) {
            Specialization::firstOrCreate(['name' => $name]);
        }
    }
}
