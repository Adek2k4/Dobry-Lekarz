<section class="space-y-6 bg-slate-800 p-6 rounded-lg text-white">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Usuń konto') }}
        </h2>

        <p class="mt-1 text-sm text-slate-200">
            {{ __('Po usunięciu konta wszystkie dane powiązane z kontem zostaną trwale usunięte. Pobierz najpierw dane, które chcesz zachować.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Usuń konto') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white">
                {{ __('Czy na pewno chcesz usunąć konto?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-200">
                {{ __('Po usunięciu konta wszystkie jego zasoby i dane zostaną trwale usunięte. Wprowadź hasło, aby potwierdzić usunięcie konta.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Hasło') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Hasło') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Anuluj') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Usuń konto') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
