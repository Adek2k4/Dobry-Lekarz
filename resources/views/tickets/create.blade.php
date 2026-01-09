<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Zgłoś lekarza') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Doctor Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-2">Zgłaszasz lekarza:</h3>
                        <div class="flex items-start gap-4">
                            <div>
                                <p class="text-xl font-medium">{{ $doctor->name }} {{ $doctor->surname }}</p>
                                @if ($doctor->doctorData && $doctor->doctorData->specialization)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $doctor->doctorData->specialization->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-500">Uwaga</h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                                    Zgłoszenie zostanie przesłane do administratora systemu. Fałszywe zgłoszenia mogą skutkować zablokowaniem konta.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Form -->
                    <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <!-- Ticket Type -->
                        <div>
                            <label for="ticket_type_id" class="block text-sm font-medium mb-2">Powód zgłoszenia <span class="text-red-500">*</span></label>
                            <select 
                                id="ticket_type_id" 
                                name="ticket_type_id" 
                                required
                                class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-500 text-gray-900 dark:text-gray-100">
                                <option value="">Wybierz powód zgłoszenia</option>
                                @foreach ($ticketTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('ticket_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ticket_type_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition">
                                Anuluj
                            </a>
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition">
                                Wyślij zgłoszenie
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
