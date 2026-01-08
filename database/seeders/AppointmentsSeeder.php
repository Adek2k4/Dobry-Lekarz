<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\OfficeHour;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pl_PL');

        // Get doctors and patients (doctors cannot be patients of other doctors)
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();
        
        $doctors = User::where('role_id', $doctorRole->id)->get();
        $patients = User::where('role_id', $patientRole->id)->get();

        if ($patients->isEmpty()) {
            $this->command->warn('No patients found. Skipping appointments seeder.');
            return;
        }

        // Date range: current date - 1 month to current date + 1 month
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now()->addMonth();
        $today = Carbon::now();

        $bookedSlots = []; // Track booked slots per doctor

        foreach ($doctors as $doctor) {
            // Get doctor's office hours
            $officeHours = OfficeHour::where('doctor_id', $doctor->id)->get();
            
            if ($officeHours->isEmpty()) {
                continue;
            }

            // Generate 5-15 appointments per doctor
            $appointmentsCount = $faker->numberBetween(5, 15);
            $attempts = 0;
            $createdCount = 0;

            while ($createdCount < $appointmentsCount && $attempts < 100) {
                $attempts++;

                // Pick random date in range
                $appointmentDate = Carbon::instance($faker->dateTimeBetween($startDate, $endDate));
                $dayOfWeek = $appointmentDate->dayOfWeek === 0 ? 7 : $appointmentDate->dayOfWeek; // Sunday = 7

                // Check if doctor works on this day
                $officeHour = $officeHours->firstWhere('day_of_week', $dayOfWeek);
                
                if (!$officeHour || !$officeHour->start_time || !$officeHour->end_time) {
                    continue;
                }

                // Parse office hours
                $startTime = Carbon::parse($officeHour->start_time);
                $endTime = Carbon::parse($officeHour->end_time);
                
                // Generate random hour within office hours (hourly slots)
                $workHours = $endTime->hour - $startTime->hour;
                if ($workHours <= 0) {
                    continue;
                }
                
                $randomHour = $faker->numberBetween($startTime->hour, $endTime->hour - 1);
                $appointmentDateTime = $appointmentDate->setTime($randomHour, 0, 0);

                // Create unique slot key
                $slotKey = $doctor->id . '_' . $appointmentDateTime->format('Y-m-d H:i:s');

                // Check if slot is already booked
                if (isset($bookedSlots[$slotKey])) {
                    continue;
                }

                // Assign random patient
                $patient = $patients->random();

                // Determine status based on date
                if ($appointmentDateTime->isPast()) {
                    // Past appointments: 80% completed, 20% cancelled
                    $status = $faker->boolean(80) ? 'completed' : 'cancelled';
                } else {
                    // Future appointments: all scheduled
                    $status = 'scheduled';
                }

                // Create appointment
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patient->id,
                    'appointment_date' => $appointmentDateTime,
                    'status' => $status,
                    'reason' => $faker->boolean(70) ? $faker->sentence() : null,
                ]);

                // Mark slot as booked
                $bookedSlots[$slotKey] = true;
                $createdCount++;
            }
        }

        $this->command->info('Appointments seeded successfully.');
    }
}
