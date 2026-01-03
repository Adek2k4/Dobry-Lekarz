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
            $doctorData->save();
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
