<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientRole = Role::where('name', 'patient')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        
        if (!$patientRole || !$doctorRole) {
            $this->command->error('Brak wymaganych ról w bazie danych!');
            return;
        }

        // Pobierz wszystkich pacjentów (tylko pacjenci mogą zgłaszać lekarzy)
        $patients = User::where('role_id', $patientRole->id)->get();
        
        // Pobierz wszystkich lekarzy
        $doctors = User::where('role_id', $doctorRole->id)->get();
        
        // Pobierz wszystkie typy zgłoszeń
        $ticketTypes = TicketType::all();

        if ($patients->isEmpty() || $doctors->isEmpty() || $ticketTypes->isEmpty()) {
            $this->command->error('Brak pacjentów, lekarzy lub typów zgłoszeń w bazie danych!');
            return;
        }

        // Losowo wybierz kilku pacjentów, którzy zgłoszą lekarzy
        $reportingPatients = $patients->random(min(30, $patients->count()));

        $createdTickets = 0;
        $alreadyReported = [];

        foreach ($reportingPatients as $patient) {
            // Każdy pacjent może zgłosić od 1 do 3 różnych lekarzy
            $numberOfReports = rand(1, 3);
            
            // Wybierz losowych lekarzy dla tego pacjenta
            $doctorsToReport = $doctors->random(min($numberOfReports, $doctors->count()));

            foreach ($doctorsToReport as $doctor) {
                // Sprawdź, czy ten pacjent już zgłosił tego lekarza
                $key = $patient->id . '-' . $doctor->id;
                
                if (isset($alreadyReported[$key])) {
                    continue; // Pomiń - już zgłosił tego lekarza
                }

                // Utwórz zgłoszenie
                Ticket::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'ticket_type_id' => $ticketTypes->random()->id,
                ]);

                $alreadyReported[$key] = true;
                $createdTickets++;
            }
        }

        $this->command->info("Utworzono {$createdTickets} zgłoszeń.");
    }
}
