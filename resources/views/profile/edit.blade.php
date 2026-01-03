<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="max-w-3xl w-full px-4 space-y-6">
            <div class="p-4 sm:p-8 bg-slate-800 shadow sm:rounded-lg text-white">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-slate-800 shadow sm:rounded-lg text-white">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-slate-800 shadow sm:rounded-lg text-white">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
