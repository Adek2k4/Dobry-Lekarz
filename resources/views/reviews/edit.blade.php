<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Edytuj opinię') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Doctor Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-2">Wizyta u:</h3>
                        <div class="flex items-start gap-4">
                            <div>
                                <p class="text-xl font-medium">{{ $appointment->doctor->name }} {{ $appointment->doctor->surname }}</p>
                                @if ($appointment->doctor->doctorData && $appointment->doctor->doctorData->specialization)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->doctor->doctorData->specialization->name }}</p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Data wizyty: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium mb-3">Ocena <span class="text-red-500">*</span></label>
                            <div class="flex gap-2" x-data="{ rating: {{ $review->rating }}, hover: 0 }">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required x-model="rating" {{ $review->rating == $i ? 'checked' : '' }}>
                                        <svg 
                                            @mouseenter="hover = {{ $i }}" 
                                            @mouseleave="hover = 0"
                                            class="w-10 h-10 transition-colors"
                                            :class="(rating >= {{ $i }} || hover >= {{ $i }}) ? 'text-yellow-500' : 'text-gray-300 dark:text-gray-600'"
                                            fill="currentColor" 
                                            viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="comment" class="block text-sm font-medium mb-2">Komentarz (opcjonalnie)</label>
                            <textarea 
                                id="comment" 
                                name="comment" 
                                rows="5" 
                                maxlength="1000"
                                class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100" 
                                placeholder="Podziel się swoimi wrażeniami z wizyty...">{{ old('comment', $review->content) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maksymalnie 1000 znaków</p>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ route('my-appointments') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition">
                                Anuluj
                            </a>
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                Zaktualizuj opinię
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
