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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
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
