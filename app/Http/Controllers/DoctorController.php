<?php

namespace App\Http\Controllers;

use App\Models\DoctorData;
use App\Models\OfficeHour;
use App\Models\Specialization;
use App\Models\Appointment;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorController extends Controller
{
    /**
     * Show the search page with doctors filtered by query.
     */
    public function search(Request $request)
    {
        // Block doctors and admins from accessing search
        if (auth()->user()->role && (auth()->user()->role->name === 'doctor' || auth()->user()->role->name === 'admin')) {
            abort(403, 'Nie masz dostępu do tej strony.');
        }

        $query = DoctorData::with(['user', 'specialization', 'address']);

        // Filter by specialization
        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->whereHas('address', function ($q) {
                $q->where('city', 'like', '%' . request('city') . '%');
            });
        }

        // Filter by doctor name
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) {
                $searchTerm = request('name');
                $q->where(function($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('surname', 'like', '%' . $searchTerm . '%')
                          ->orWhereRaw("CONCAT(name, ' ', surname) LIKE ?", ['%' . $searchTerm . '%']);
                });
            });
        }

        $doctors = $query->paginate(12);
        
        // Calculate average rating for each doctor
        foreach ($doctors as $doctor) {
            $averageRating = \App\Models\Review::where('doctor_id', $doctor->user_id)
                ->avg('rating');
            $doctor->averageRating = $averageRating ? round($averageRating, 1) : null;
        }
        
        $specializations = Specialization::all();

        return view('search', [
            'doctors' => $doctors,
            'specializations' => $specializations,
        ]);
    }

    /**
     * Show the doctor profile page.
     */
    public function show($slug)
    {
        // Extract user ID from slug (format: name-surname-id)
        $userId = (int) substr($slug, strrpos($slug, '-') + 1);
        
        $doctorData = DoctorData::with(['user', 'specialization', 'address'])
            ->whereHas('user', function ($q) use ($userId) {
                $q->where('id', $userId);
            })
            ->firstOrFail();

        // Calculate average rating from reviews
        $averageRating = \App\Models\Review::where('doctor_id', $doctorData->user_id)
            ->avg('rating');
        
        $reviewsCount = \App\Models\Review::where('doctor_id', $doctorData->user_id)
            ->count();

        // Load office hours and map to weekly schedule (Mon=1 ... Sun=7)
        $hours = OfficeHour::where('doctor_id', $doctorData->user_id)
            ->get()
            ->keyBy('day_of_week');

        $weeklySchedule = [];
        for ($day = 1; $day <= 7; $day++) {
            $row = $hours->get($day);
            if ($row && $row->start_time && $row->end_time) {
                // Format times HH:MM from HH:MM:SS
                $weeklySchedule[$day] = [
                    'open' => true,
                    'start' => substr((string) $row->start_time, 0, 5),
                    'end' => substr((string) $row->end_time, 0, 5),
                ];
            } else {
                $weeklySchedule[$day] = [
                    'open' => false,
                    'start' => null,
                    'end' => null,
                ];
            }
        }

        // Build available days (next 2 months) and time slots based on office hours
        // Fetch existing appointments for this doctor
        $bookedSlots = Appointment::where('doctor_id', $doctorData->user_id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', Carbon::now())
            ->get()
            ->groupBy(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->toDateString();
            })
            ->map(function ($group) {
                return $group->map(function ($appointment) {
                    return Carbon::parse($appointment->appointment_date)->format('H:i');
                })->toArray();
            })
            ->toArray();

        $availableDays = [];
        $timeSlots = [];
        $today = Carbon::today();
        for ($i = 0; $i < 60; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = (int) $date->dayOfWeekIso; // 1 (Mon) ... 7 (Sun)
            $schedule = $weeklySchedule[$dayOfWeek];

            if ($schedule['open']) {
                $slots = [];
                $start = Carbon::createFromFormat('H:i', $schedule['start']);
                $end = Carbon::createFromFormat('H:i', $schedule['end']);

                while ($start->lessThan($end)) {
                    $timeString = $start->format('H:i');
                    // Exclude booked slots
                    if (!isset($bookedSlots[$date->toDateString()]) || !in_array($timeString, $bookedSlots[$date->toDateString()])) {
                        $slots[] = $timeString;
                    }
                    $start->addMinutes(60);
                }

                // Only add day if there are available slots
                if (count($slots) > 0) {
                    $availableDays[] = [
                        'date' => $date->toDateString(),
                        'label' => $date->format('d.m.Y'),
                    ];
                    $timeSlots[$date->toDateString()] = $slots;
                }
            }
        }

        return view('doctor.show', [
            'doctor' => $doctorData,
            'averageRating' => $averageRating,
            'reviewsCount' => $reviewsCount,
            'weeklySchedule' => $weeklySchedule,
            'availableDays' => $availableDays,
            'timeSlots' => $timeSlots,
            'reviews' => \App\Models\Review::with('patient')
                ->where('doctor_id', $doctorData->user_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    /**
     * Store a new appointment.
     */
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check if slot is still available
        $exists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $appointmentDateTime)
            ->where('status', 'scheduled')
            ->exists();

        if ($exists) {
            return back()->withErrors(['appointment_time' => 'Ten termin jest już zajęty. Proszę wybrać inny.'])->withInput();
        }

        // Create appointment
        Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => auth()->id(),
            'appointment_date' => $appointmentDateTime,
            'status' => 'scheduled',
            'reason' => $validated['reason'],
        ]);

        $slug = $request->input('slug');
        return redirect()->route('doctor.show', ['slug' => $slug])->with('success', 'Wizyta została pomyślnie zarezerwowana!');
    }

    /**
     * Show user's appointments (different for patients and doctors).
     */
    public function myAppointments()
    {
        $user = auth()->user();
        
        // Block admins from accessing my-appointments
        if ($user->role && $user->role->name === 'admin') {
            abort(403, 'Nie masz dostępu do tej strony.');
        }
        
        $isDoctor = $user->role && $user->role->name === 'doctor';

        if ($isDoctor) {
            // For doctors: show appointments where they are the doctor
            $appointments = Appointment::with(['patient', 'patient.doctorData'])
                ->where('doctor_id', $user->id)
                ->orderBy('appointment_date', 'desc')
                ->paginate(15);
        } else {
            // For patients: show appointments where they are the patient
            $appointments = Appointment::with(['doctor', 'doctor.doctorData.specialization', 'doctor.doctorData.address'])
                ->where('patient_id', $user->id)
                ->orderBy('appointment_date', 'desc')
                ->paginate(15);

            // Check which appointments already have reviews
            $reviewedDoctors = Review::where('patient_id', $user->id)
                ->whereIn('doctor_id', $appointments->pluck('doctor_id'))
                ->pluck('doctor_id')
                ->toArray();
        }

        return view('my-appointments', [
            'appointments' => $appointments,
            'isDoctor' => $isDoctor,
            'reviewedDoctors' => $isDoctor ? [] : $reviewedDoctors,
        ]);
    }

    /**
     * Update appointment status (only for doctors).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = auth()->user();
        
        // Check if user is the doctor for this appointment
        if ($appointment->doctor_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status wizyty został zaktualizowany.');
    }

    /**
     * Show the review edit form.
     */
    public function editReview(Request $request)
    {
        $user = auth()->user();
        $appointmentId = $request->query('appointment');
        
        // Get the appointment
        $appointment = Appointment::with(['doctor.doctorData.specialization', 'doctor.doctorData.address'])
            ->findOrFail($appointmentId);

        // Verify appointment belongs to patient and is completed
        if ($appointment->patient_id !== $user->id || $appointment->status !== 'completed') {
            abort(403);
        }

        // Get existing review
        $review = Review::where('doctor_id', $appointment->doctor_id)
            ->where('patient_id', $user->id)
            ->firstOrFail();

        return view('reviews.edit', [
            'appointment' => $appointment,
            'review' => $review,
        ]);
    }

    /**
     * Show the review form for a completed appointment.
     */
    public function createReview(Request $request)
    {
        $appointmentId = $request->input('appointment');
        $appointment = Appointment::with(['doctor', 'doctor.doctorData.specialization'])
            ->where('id', $appointmentId)
            ->where('patient_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if review already exists for this doctor from this patient
        $existingReview = \App\Models\Review::where('doctor_id', $appointment->doctor_id)
            ->where('patient_id', auth()->id())
            ->first();
        if ($existingReview) {
            return redirect()->route('my-appointments')->with('error', 'Już wystawiłeś opinię dla tego lekarza.');
        }

        return view('reviews.create', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * Store or update a review.
     */
    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::where('id', $validated['appointment_id'])
            ->where('patient_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if review already exists for this doctor from this patient
        $existingReview = \App\Models\Review::where('doctor_id', $appointment->doctor_id)
            ->where('patient_id', auth()->id())
            ->first();
        
        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $validated['rating'],
                'content' => $validated['comment'],
            ]);
            return redirect()->route('my-appointments')->with('success', 'Opinia została zaktualizowana!');
        }

        // Create new review
        \App\Models\Review::create([
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => auth()->id(),
            'rating' => $validated['rating'],
            'content' => $validated['comment'],
        ]);

        return redirect()->route('my-appointments')->with('success', 'Dziękujemy za wystawienie opinii!');
    }

    /**
     * Show the ticket creation form.
     */
    public function createTicket(Request $request)
    {
        $doctorId = $request->query('doctor');
        
        // Get doctor data
        $doctor = User::with(['doctorData.specialization'])->findOrFail($doctorId);
        
        // Verify user is not reporting themselves
        if (auth()->id() === $doctorId) {
            abort(403, 'Nie możesz zgłosić samego siebie.');
        }
        
        // Check if ticket already exists
        $existingTicket = Ticket::where('doctor_id', $doctorId)
            ->where('patient_id', auth()->id())
            ->first();
        
        if ($existingTicket) {
            return redirect()->back()->with('error', 'Już zgłosiłeś tego lekarza.');
        }
        
        // Get ticket types from database
        $ticketTypes = \DB::table('ticket_types')->get();
        
        return view('tickets.create', [
            'doctor' => $doctor,
            'ticketTypes' => $ticketTypes,
        ]);
    }

    /**
     * Store a new ticket.
     */
    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);
        
        $doctorId = $validated['doctor_id'];
        
        // Verify user is not reporting themselves
        if (auth()->id() === $doctorId) {
            return back()->with('error', 'Nie możesz zgłosić samego siebie.');
        }
        
        // Check if ticket already exists
        $existingTicket = Ticket::where('doctor_id', $doctorId)
            ->where('patient_id', auth()->id())
            ->first();
        
        if ($existingTicket) {
            return back()->with('error', 'Już zgłosiłeś tego lekarza.');
        }
        
        // Create ticket
        Ticket::create([
            'doctor_id' => $doctorId,
            'patient_id' => auth()->id(),
            'ticket_type_id' => $validated['ticket_type_id'],
        ]);
        
        return redirect()->back()->with('success', 'Zgłoszenie zostało pomyślnie wysłane.');
    }
}
