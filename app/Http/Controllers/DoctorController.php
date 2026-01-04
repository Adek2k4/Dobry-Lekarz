<?php

namespace App\Http\Controllers;

use App\Models\DoctorData;
use App\Models\Specialization;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Show the search page with doctors filtered by query.
     */
    public function search(Request $request)
    {
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
                $q->where('name', 'like', '%' . request('name') . '%');
            });
        }

        $doctors = $query->paginate(12);
        $specializations = Specialization::all();

        return view('search', [
            'doctors' => $doctors,
            'specializations' => $specializations,
        ]);
    }
}
