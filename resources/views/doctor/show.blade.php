<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profil lekarza') }}
            </h2>
            @auth
                @if (auth()->user()->id !== $doctor->user_id)
                    <a href="{{ route('tickets.create', ['doctor' => $doctor->user_id]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Zgłoś lekarza
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Doctor Profile Section -->
                    <div class="flex gap-8 mb-8">
                        <!-- Left Column - Profile Picture and Rating -->
                        <div class="flex-shrink-0">
                            <div class="w-48">
                                @if ($doctor->profile_picture)
                                    <img src="{{ url('storage/' . $doctor->profile_picture) }}" alt="{{ $doctor->user->name }}" class="w-48 h-48 object-cover rounded-lg shadow-lg mb-4" onerror="this.src='{{ asset('profile.png') }}'">
                                @else
                                    <img src="{{ asset('profile.png') }}" alt="{{ $doctor->user->name }}" class="w-48 h-48 object-cover rounded-lg shadow-lg mb-4">
                                @endif
                                
                                <!-- Rating -->
                                <div class="text-center">
                                    @if ($averageRating && $reviewsCount > 0)
                                        <div class="flex items-center justify-center mb-1">
                                            <span class="text-2xl font-bold text-yellow-500">{{ number_format($averageRating, 1) }}</span>
                                            <span class="text-gray-600 dark:text-gray-400 ml-1">/5</span>
                                        </div>
                                        <div class="flex justify-center mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($averageRating))
                                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">({{ $reviewsCount }} {{ $reviewsCount === 1 ? 'ocena' : 'ocen' }})</p>
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">Brak ocen</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Doctor Info -->
                        <div class="flex-grow">
                            <h1 class="text-3xl font-bold mb-2">{{ $doctor->user->name }} {{ $doctor->user->surname }}</h1>
                            
                            <div class="mb-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">
                                    {{ $doctor->specialization->name ?? 'Brak specjalizacji' }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4 text-gray-700 dark:text-gray-300">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $doctor->address->street ?? '' }} {{ $doctor->address->house_number ?? '' }}, {{ $doctor->address->postal_code ?? '' }} {{ $doctor->address->city ?? 'Brak miasta' }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $doctor->user->phone ?? 'Brak telefonu' }}</span>
                                </div>
                            </div>

                            @if ($doctor->description)
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold mb-2">O lekarzu</h3>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $doctor->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Weekly Schedule Full Width -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-3">Godziny przyjęć</h3>
                        @php
                            $days = [1 => 'Pon', 2 => 'Wt', 3 => 'Śr', 4 => 'Czw', 5 => 'Pt', 6 => 'Sob', 7 => 'Nd'];
                        @endphp
                        <div class="grid grid-cols-7 gap-3 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            @foreach ($days as $d => $label)
                                <div class="text-center">
                                    <div class="font-semibold text-sm mb-1">{{ $label }}</div>
                                    <div class="text-sm">
                                        @if (!empty($weeklySchedule[$d]) && $weeklySchedule[$d]['open'])
                                            {{ $weeklySchedule[$d]['start'] }}–{{ $weeklySchedule[$d]['end'] }}
                                        @else
                                            <span class="text-gray-500">zamknięte</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Appointment Calendar Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8" x-data="{
                        selectedDay: '{{ $availableDays[0]['date'] ?? '' }}',
                        allSlots: @js($timeSlots),
                        get currentSlots() {
                            return this.allSlots[this.selectedDay] || [];
                        }
                    }">
                        <h2 class="text-2xl font-bold mb-6">Umów wizytę</h2>
                        
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                            @if (session('success'))
                                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-md">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('appointments.store', ['doctor_id' => $doctor->user_id]) }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="doctor_id" value="{{ $doctor->user_id }}">
                                <input type="hidden" name="slug" value="{{ Str::slug($doctor->user->name . ' ' . $doctor->user->surname) }}-{{ $doctor->user_id }}">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Date Picker (only open days) -->
                                    <div>
                                        <label for="appointment_date" class="block text-sm font-medium mb-2">Data wizyty</label>
                                        <select 
                                            id="appointment_date" 
                                            name="appointment_date" 
                                            class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                            x-model="selectedDay"
                                            required>
                                            @forelse ($availableDays as $availableDay)
                                                <option value="{{ $availableDay['date'] }}">{{ $availableDay['label'] }}</option>
                                            @empty
                                                <option value="">Brak dostępnych terminów</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <!-- Time Picker (only slots for selected day) -->
                                    <div>
                                        <label for="appointment_time" class="block text-sm font-medium mb-2">Godzina</label>
                                        <select 
                                            id="appointment_time" 
                                            name="appointment_time" 
                                            class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500"
                                            required>
                                            <template x-for="slot in currentSlots" :key="slot">
                                                <option :value="slot" x-text="slot"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div>
                                    <label for="reason" class="block text-sm font-medium mb-2">Powód wizyty (opcjonalnie)</label>
                                    <textarea 
                                        id="reason" 
                                        name="reason" 
                                        rows="4" 
                                        class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500" 
                                        placeholder="Opisz krótko powód wizyty..."></textarea>
                                </div>

                                <!-- Submit Button -->
                                @auth
                                    @if (auth()->id() !== $doctor->user_id)
                                        <div class="flex justify-end">
                                            <button 
                                                type="submit" 
                                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-lg transition flex items-center"
                                                :disabled="currentSlots.length === 0"
                                                :class="{'opacity-60 cursor-not-allowed': currentSlots.length === 0}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Potwierdź rezerwację
                                            </button>
                                        </div>
                                    @endif
                                @endauth
                            </form>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-8">
                        <h2 class="text-2xl font-bold mb-6">Opinie pacjentów</h2>
                        
                        @if ($reviews->isEmpty())
                            <div class="text-center py-8 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Brak opinii dla tego lekarza</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($reviews as $review)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $review->patient->name }} {{ $review->patient->surname }}</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('d.m.Y') }}</span>
                                                </div>
                                                <div class="flex">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        @if ($review->content)
                                            <p class="text-gray-700 dark:text-gray-300 mt-3">{{ $review->content }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
