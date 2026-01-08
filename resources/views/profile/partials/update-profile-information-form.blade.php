<section class="bg-slate-800 p-6 rounded-lg text-white">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Informacje o profilu') }}
        </h2>

        <p class="mt-1 text-sm text-slate-200">
            {{ __('Zaktualizuj swoje dane konta i adres email.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Imię')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="surname" :value="__('Nazwisko')" />
            <x-text-input id="surname" name="surname" type="text" class="mt-1 block w-full" :value="old('surname', $user->surname)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('surname')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Telefon')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-200">
                        {{ __('Twój adres email nie został zweryfikowany.') }}

                            <button form="send-verification" class="underline text-sm text-slate-200 hover:opacity-80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Kliknij, aby ponownie wysłać mail weryfikacyjny.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('Nowy link weryfikacyjny został wysłany na Twój adres e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if (isset($user->role) && $user->role->name === 'doctor')
            <div class="pt-4 border-t">
                <h3 class="text-md font-medium">{{ __('Dane lekarza') }}</h3>

                <div class="mt-3">
                    <x-input-label for="specialization_id" :value="__('Specjalizacja')" />
                    <select id="specialization_id" name="specialization_id" class="block mt-1 w-full rounded-md bg-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" style="background-color:#0f172a !important; color:#ffffff !important;">
                        <option value="">Wybierz specjalizację</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->id }}" {{ old('specialization_id', optional($user->doctorData)->specialization_id) == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('specialization_id')" class="mt-2" />
                </div>

                <div class="mt-3">
                    <x-input-label for="description" :value="__('Opis profilu')" />
                    <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" style="background-color:#0f172a !important; color:#ffffff !important;">{{ old('description', optional($user->doctorData)->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="mt-3">
                    <x-input-label for="profile_picture" :value="__('Zdjęcie profilowe')" />
                    <div class="mt-2 flex items-center space-x-4">
                        <img src="{{ optional($user->doctorData)->profile_picture ? url('storage/' . optional($user->doctorData)->profile_picture) : asset('profile.png') }}" alt="Zdjęcie profilowe" class="w-24 h-24 object-cover rounded-lg">
                        <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="block w-full text-white file:bg-indigo-600 file:text-white file:rounded-md file:px-3 file:py-2 file:border-0" />
                    </div>
                    <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                </div>

                <h4 class="mt-4 font-medium">{{ __('Adres gabinetu') }}</h4>
                <div class="grid grid-cols-1 gap-2 mt-2">
                    <div>
                        <x-input-label for="city" :value="__('Miasto')" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', optional(optional($user->doctorData)->address)->city)" />
                        <x-input-error class="mt-2" :messages="$errors->get('city')" />
                    </div>

                    <div>
                        <x-input-label for="postal_code" :value="__('Kod pocztowy')" />
                        <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', optional(optional($user->doctorData)->address)->postal_code)" />
                        <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                    </div>

                    <div>
                        <x-input-label for="street" :value="__('Ulica')" />
                        <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', optional(optional($user->doctorData)->address)->street)" />
                        <x-input-error class="mt-2" :messages="$errors->get('street')" />
                    </div>

                    <div>
                        <x-input-label for="house_number" :value="__('Nr domu/lokalu')" />
                        <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block w-full" :value="old('house_number', optional(optional($user->doctorData)->address)->house_number)" />
                        <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
                    </div>
                </div>

                <h4 class="mt-6 font-medium">{{ __('Godziny otwarcia') }}</h4>
                <p class="text-sm text-slate-300">Pozostaw puste, jeśli gabinet jest nieczynny w danym dniu.</p>

                @php
                    $days = [
                        1 => 'Poniedziałek',
                        2 => 'Wtorek',
                        3 => 'Środa',
                        4 => 'Czwartek',
                        5 => 'Piątek',
                        6 => 'Sobota',
                        7 => 'Niedziela',
                    ];
                    $hoursByDay = isset($user->officeHours) ? $user->officeHours->keyBy('day_of_week') : collect();
                @endphp

                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($days as $d => $label)
                        @php
                            $existing = $hoursByDay->get($d);
                            $defaultOpen = old('office_hours.'.$d.'.is_open') !== null
                                ? (bool) old('office_hours.'.$d.'.is_open')
                                : (optional($existing)->start_time && optional($existing)->end_time);
                        @endphp
                        <div class="bg-slate-700 rounded-md p-3" x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium">{{ $label }}</div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only" x-model="open" name="office_hours[{{ $d }}][is_open]" value="1">
                                    <div class="relative w-10 h-5 bg-slate-600 rounded-full">
                                        <div class="absolute top-0 left-0 w-5 h-5 rounded-full transition"
                                             :class="open ? 'translate-x-5 bg-green-400' : 'translate-x-0 bg-gray-300'">
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <input type="hidden" name="office_hours[{{ $d }}][day_of_week]" value="{{ $d }}" />
                            <div class="grid grid-cols-2 gap-2" x-show="open">
                                <div>
                                    <x-input-label :value="__('Od')" />
                                    <input x-ref="start" type="time" name="office_hours[{{ $d }}][start_time]" value="{{ old('office_hours.'.$d.'.start_time', optional($existing)->start_time) }}" class="mt-1 block w-full rounded-md bg-slate-700 text-white border-slate-600" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Do')" />
                                    <input x-ref="end" type="time" name="office_hours[{{ $d }}][end_time]" value="{{ old('office_hours.'.$d.'.end_time', optional($existing)->end_time) }}" class="mt-1 block w-full rounded-md bg-slate-700 text-white border-slate-600" />
                                </div>
                            </div>
                            <div x-show="!open" class="text-sm text-slate-300 mt-2">Gabinet nieczynny</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Zapisz') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-200"
                >{{ __('Zapisano.') }}</p>
            @endif
        </div>
    </form>
</section>
