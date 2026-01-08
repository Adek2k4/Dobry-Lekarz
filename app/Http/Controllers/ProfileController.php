<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $data = ['user' => $request->user()];
        if ($request->user()->role && $request->user()->role->name === 'doctor') {
            $data['specializations'] = \App\Models\Specialization::orderBy('name')->get();
        }

        return view('profile.edit', $data);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $request->user()->fill($validated);

        // fill surname and phone if present
        if (isset($validated['surname'])) {
            $request->user()->surname = $validated['surname'];
        }
        if (isset($validated['phone'])) {
            $request->user()->phone = $validated['phone'];
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // if doctor, update doctor_data and address
        if ($request->user()->role && $request->user()->role->name === 'doctor') {
            $user = $request->user();
            $doctorData = $user->doctorData;
            if (! $doctorData) {
                $doctorData = new \App\Models\DoctorData();
                $doctorData->user_id = $user->id;
            }

            // address
            $addressData = [
                'city' => $validated['city'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'street' => $validated['street'] ?? null,
                'house_number' => $validated['house_number'] ?? null,
            ];

            if ($doctorData->address_id) {
                $address = \App\Models\Address::find($doctorData->address_id);
                if ($address) {
                    $address->update($addressData);
                } else {
                    $address = \App\Models\Address::create($addressData);
                    $doctorData->address_id = $address->id;
                }
            } else {
                $address = \App\Models\Address::create($addressData);
                $doctorData->address_id = $address->id;
            }

            $doctorData->specialization_id = $validated['specialization_id'] ?? $doctorData->specialization_id;
            $doctorData->description = $validated['description'] ?? $doctorData->description;
            
            // handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // delete old picture if exists
                if ($doctorData->profile_picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($doctorData->profile_picture)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($doctorData->profile_picture);
                }
                
                $file = $request->file('profile_picture');
                $path = $file->store('profile_pictures', 'public');
                \Log::info('Profile picture uploaded: ' . $path);
                $doctorData->profile_picture = $path;
            }
            
            $doctorData->save();

            // Office hours: save open/closed per day with nullable times
            $hours = $request->input('office_hours', []);
            if (is_array($hours)) {
                foreach (range(1,7) as $day) {
                    $row = $hours[$day] ?? [];
                    $isOpen = isset($row['is_open']);
                    $start = $row['start_time'] ?? null;
                    $end = $row['end_time'] ?? null;

                    if ($isOpen) {
                        // Save provided times (or null if missing)
                        \App\Models\OfficeHour::updateOrCreate(
                            ['doctor_id' => $user->id, 'day_of_week' => $day],
                            ['start_time' => $start ?: null, 'end_time' => $end ?: null]
                        );
                    } else {
                        // Mark closed: store nulls
                        \App\Models\OfficeHour::updateOrCreate(
                            ['doctor_id' => $user->id, 'day_of_week' => $day],
                            ['start_time' => null, 'end_time' => null]
                        );
                    }
                }
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
