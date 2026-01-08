<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with patients list.
     */
    public function dashboard(Request $request)
    {
        $tab = $request->query('tab', 'patients');
        
        $data = [];
        
        switch ($tab) {
            case 'patients':
                $data = $this->getPatientsData($request);
                break;
            case 'doctors':
                $data = $this->getDoctorsData($request);
                break;
            case 'appointments':
                $data = $this->getAppointmentsData($request);
                break;
            case 'reviews':
                $data = $this->getReviewsData($request);
                break;
            case 'office_hours':
                $data = $this->getOfficeHoursData($request);
                break;
            case 'addresses':
                $data = $this->getAddressesData($request);
                break;
        }
        
        return view('admin.dashboard', array_merge(['currentTab' => $tab], $data));
    }
    
    private function getPatientsData(Request $request)
    {
        $patientRole = Role::where('name', 'patient')->first();
        
        $query = User::where('role_id', $patientRole->id);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $patients = $query->paginate(15)->appends(['tab' => 'patients', 'search' => $request->search]);
        
        return ['patients' => $patients];
    }
    
    private function getDoctorsData(Request $request)
    {
        $doctorRole = Role::where('name', 'doctor')->first();
        
        $query = User::with(['doctorData.specialization', 'doctorData.address'])
            ->where('role_id', $doctorRole->id);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $doctors = $query->paginate(15)->appends(['tab' => 'doctors', 'search' => $request->search]);
        
        return ['doctors' => $doctors];
    }
    
    private function getAppointmentsData(Request $request)
    {
        $query = Appointment::with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc');
        
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->where(function($q) use ($userId) {
                $q->where('doctor_id', $userId)
                  ->orWhere('patient_id', $userId);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('doctor', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('surname', 'like', "%{$search}%");
                })
                ->orWhereHas('patient', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('surname', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort === 'date_asc') {
                $query->orderBy('appointment_date', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('appointment_date', 'desc');
            }
        }
        
        $appointments = $query->paginate(15)->appends([
            'tab' => 'appointments',
            'search' => $request->search,
            'status' => $request->status,
            'sort' => $request->sort,
            'user_id' => $request->user_id
        ]);
        
        return ['appointments' => $appointments];
    }
    
    private function getReviewsData(Request $request)
    {
        $query = Review::with(['doctor', 'patient'])
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->where(function($q) use ($userId) {
                $q->where('doctor_id', $userId)
                  ->orWhere('patient_id', $userId);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('doctor', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('surname', 'like', "%{$search}%");
                })
                ->orWhereHas('patient', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('surname', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort === 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'rating_asc') {
                $query->orderBy('rating', 'asc');
            } elseif ($sort === 'rating_desc') {
                $query->orderBy('rating', 'desc');
            }
        }
        
        $reviews = $query->paginate(15)->appends([
            'tab' => 'reviews',
            'search' => $request->search,
            'rating' => $request->rating,
            'sort' => $request->sort,
            'user_id' => $request->user_id
        ]);
        
        return ['reviews' => $reviews];
    }
    
    private function getOfficeHoursData(Request $request)
    {
        $query = \App\Models\OfficeHour::with(['doctor'])
            ->orderBy('doctor_id')
            ->orderBy('day_of_week');
        
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('doctor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%");
            });
        }
        
        $officeHours = $query->paginate(15)->appends([
            'tab' => 'office_hours',
            'search' => $request->search,
            'doctor_id' => $request->doctor_id
        ]);
        
        return ['officeHours' => $officeHours];
    }
    
    private function getAddressesData(Request $request)
    {
        $query = \App\Models\Address::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('city', 'like', "%{$search}%")
                  ->orWhere('street', 'like', "%{$search}%")
                  ->orWhere('postal_code', 'like', "%{$search}%");
            });
        }
        
        $addresses = $query->paginate(15)->appends([
            'tab' => 'addresses',
            'search' => $request->search
        ]);
        
        return ['addresses' => $addresses];
    }
    
    /**
     * Get user appointments (for modal).
     */
    public function getUserAppointments($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->role->name === 'doctor') {
            $appointments = Appointment::with(['patient'])
                ->where('doctor_id', $userId)
                ->orderBy('appointment_date', 'desc')
                ->get();
        } else {
            $appointments = Appointment::with(['doctor'])
                ->where('patient_id', $userId)
                ->orderBy('appointment_date', 'desc')
                ->get();
        }
        
        return response()->json(['appointments' => $appointments]);
    }
    
    /**
     * Get user reviews (for modal).
     */
    public function getUserReviews($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->role->name === 'doctor') {
            $reviews = Review::with(['patient'])
                ->where('doctor_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $reviews = Review::with(['doctor'])
                ->where('patient_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return response()->json(['reviews' => $reviews]);
    }
    
    /**
     * Get doctor office hours (for modal).
     */
    public function getDoctorOfficeHours($userId)
    {
        $officeHours = \App\Models\OfficeHour::where('doctor_id', $userId)
            ->orderBy('day_of_week')
            ->get();
        
        return response()->json(['officeHours' => $officeHours]);
    }
}
