<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Znajdź specjalistę') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('search') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imię i nazwisko</label>
                            <input type="text" name="name" id="name" value="{{ request('name') }}" placeholder="Szukaj lekarza..." class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label for="specialization_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specjalizacja</label>
                            <select name="specialization_id" id="specialization_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Wszystkie</option>
                                @foreach ($specializations as $spec)
                                    <option value="{{ $spec->id }}" {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miasto</label>
                            <input type="text" name="city" id="city" value="{{ request('city') }}" placeholder="np. Warszawa" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">Szukaj</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="grid grid-cols-1 gap-4">
                @forelse ($doctors as $doctor)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex gap-6">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    @if ($doctor->profile_picture)
                                        <img src="{{ url('storage/' . $doctor->profile_picture) }}" alt="{{ $doctor->user->name }}" class="w-32 h-32 object-cover rounded-lg" onerror="this.src='{{ asset('profile.png') }}'">
                                    @else
                                        <img src="{{ asset('profile.png') }}" alt="{{ $doctor->user->name }}" class="w-32 h-32 object-cover rounded-lg">
                                    @endif
                                    <div class="mt-4">
                                        <a href="{{ url('/' . Str::slug($doctor->user->name . ' ' . $doctor->user->surname) . '-' . $doctor->user->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">Umów wizytę</a>
                                    </div>
                                </div>

                                <!-- Doctor Info -->
                                <div class="flex-grow">
                                    <h3 class="text-lg font-semibold">{{ $doctor->user->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $doctor->specialization->name ?? 'Brak specjalizacji' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $doctor->address->city ?? 'Brak miasta' }}, {{ $doctor->address->postal_code ?? '' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $doctor->address->street ?? '' }} {{ $doctor->address->house_number ?? '' }}</p>

                                    @if ($doctor->description)
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-3">{{ Str::limit($doctor->description, 150) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-600 dark:text-gray-400">
                            Brak wyników. Spróbuj zmienić kryteria wyszukiwania.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($doctors->hasPages())
                <div class="mt-6">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
