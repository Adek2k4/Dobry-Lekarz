<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pl_PL');

        // Get all completed appointments
        $completedAppointments = Appointment::where('status', 'completed')
            ->with(['doctor', 'patient'])
            ->get();

        // Group by doctor-patient pairs (one review per pair, not per appointment)
        $doctorPatientPairs = $completedAppointments
            ->groupBy(function ($appointment) {
                return $appointment->doctor_id . '_' . $appointment->patient_id;
            });

        $existingReviews = [];

        foreach ($doctorPatientPairs as $pairKey => $appointments) {
            // Only 60-70% of patients leave reviews
            if (!$faker->boolean(65)) {
                continue;
            }

            $appointment = $appointments->first();
            
            // Check if review already exists (shouldn't happen but safety check)
            $reviewKey = $appointment->doctor_id . '_' . $appointment->patient_id;
            if (isset($existingReviews[$reviewKey])) {
                continue;
            }

            // Generate rating (weighted towards positive: 70% get 4-5 stars)
            $rating = $faker->boolean(70) 
                ? $faker->numberBetween(4, 5) 
                : $faker->numberBetween(1, 3);

            // Generate content (80% of reviews have comments)
            $content = null;
            if ($faker->boolean(80)) {
                $positiveComments = [
                    'Bardzo profesjonalny lekarz, polecam!',
                    'Świetna obsługa, dokładne badanie.',
                    'Kompetentny i miły lekarz.',
                    'Wszystko przebiegło sprawnie, jestem zadowolony.',
                    'Polecam, lekarz wysłuchał wszystkich moich problemów.',
                    'Wizyta przebiegła pomyślnie, otrzymałem szczegółowe zalecenia.',
                    'Dobry lekarz, wrócę na kolejną wizytę.',
                    'Bardzo pomocny i cierpliwy lekarz.',
                    'Świetne podejście do pacjenta.',
                    'Jestem bardzo zadowolona z wizyty.',
                ];

                $neutralComments = [
                    'Wizyta standardowa, nic szczególnego.',
                    'Lekarz w porządku.',
                    'Wszystko OK.',
                    'Wizyta przebiegła sprawnie.',
                ];

                $negativeComments = [
                    'Długi czas oczekiwania w kolejce.',
                    'Wizyta była zbyt krótka.',
                    'Lekarz się spóźnił.',
                    'Nie otrzymałem dokładnych informacji.',
                    'Mało czasu poświęconego na konsultację.',
                ];

                if ($rating >= 4) {
                    $content = $faker->randomElement($positiveComments);
                } elseif ($rating == 3) {
                    $content = $faker->randomElement($neutralComments);
                } else {
                    $content = $faker->randomElement($negativeComments);
                }
            }

            Review::create([
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'rating' => $rating,
                'content' => $content,
            ]);

            $existingReviews[$reviewKey] = true;
        }

        $this->command->info('Reviews seeded successfully.');
    }
}
