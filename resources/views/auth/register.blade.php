<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="bg-slate-800 p-6 rounded-lg text-white" x-data="{ isDoctorSelected: {{ old('role_id') && $roles->where('name', 'doctor')->first() && old('role_id') == $roles->where('name', 'doctor')->first()->id ? 'true' : 'false' }} }">
        @csrf

        <!-- Imię -->
        <div>
            <x-input-label for="name" :value="__('Imię')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Nazwisko -->
        <div class="mt-4">
            <x-input-label for="surname" :value="__('Nazwisko')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Hasło')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- (role select removed — use radio buttons below) -->

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Powtórz hasło')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <!-- Telefon -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Telefon')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Rola (radio) -->
        <div class="mt-4">
            <x-input-label :value="__('Jesteś')" />
            <div class="flex gap-4 mt-2">
                @foreach($roles as $role)
                    <label class="inline-flex items-center">
                        <input type="radio" name="role_id" value="{{ $role->id }}" class="mr-2" 
                               {{ old('role_id') == $role->id ? 'checked' : '' }}
                               @change="isDoctorSelected = ('{{ $role->name }}' === 'doctor')">
                        <span>{{ ucfirst($role->name) == 'Doctor' ? 'Lekarz' : 'Pacjent' }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Pola dla lekarza (ukryte domyślnie, pokazywane przez Alpine.js) -->
        <div id="doctor-fields" class="mt-4" x-show="isDoctorSelected" style="display: none;">
            <h3 class="font-semibold">Dane lekarza</h3>

            <div class="mt-2">
                <x-input-label for="specialization_id" :value="__('Specjalizacja')" />
                    <select id="specialization_id" name="specialization_id" class="block mt-1 w-full rounded-md bg-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" style="background-color:#0f172a !important; color:#ffffff !important;">
                    <option value="">Wybierz specjalizację</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec->id }}" {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('specialization_id')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="description" :value="__('Opis profilu')" />
                    <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" style="background-color:#0f172a !important; color:#ffffff !important;">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <h4 class="mt-4 font-medium">Adres gabinetu</h4>
            <div class="mt-2">
                <x-input-label for="city" :value="__('Miasto')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="postal_code" :value="__('Kod pocztowy')" />
                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="street" :value="__('Ulica')" />
                <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" />
                <x-input-error :messages="$errors->get('street')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-input-label for="house_number" :value="__('Nr domu/lokalu')" />
                <x-text-input id="house_number" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')" />
                <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-white hover:opacity-80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Masz już konto?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Zarejestruj się') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
