<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Address;
use App\Models\DoctorData;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::whereIn('name', ['doctor','patient'])->get();
        $specializations = Specialization::orderBy('name')->get();
        return view('auth.register', compact('roles','specializations'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'phone.regex' => 'Telefon musi składać się z 9 cyfr.',
            'postal_code.regex' => 'Kod pocztowy musi mieć format XX-XXX.',
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]{9}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ], $messages);

        // additional validation for doctor-specific fields (when registering as doctor)
        $role = Role::find($request->role_id);
        if ($role && $role->name === 'doctor') {
            $request->validate([
                'specialization_id' => ['required', 'exists:specializations,id'],
                'description' => ['required', 'string'],
                'city' => ['required', 'string', 'max:255'],
                'postal_code' => ['required', 'regex:/^\d{2}-\d{3}$/'],
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:50'],
            ], $messages);
        }

        // Force roles allowed for registration to doctor or patient
        if (! $role || ! in_array($role->name, ['doctor','patient'])) {
            return back()->withErrors(['role_id' => 'Nieprawidłowa rola.']);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            // If doctor, create address and doctor_data
            if ($role->name === 'doctor') {
                $address = Address::create([
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'street' => $request->street,
                    'house_number' => $request->house_number,
                ]);

                DoctorData::create([
                    'user_id' => $user->id,
                    'address_id' => $address->id,
                    'specialization_id' => $request->specialization_id,
                    'description' => $request->description,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Wystąpił błąd podczas rejestracji.']);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}
