<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Godziny otwarcia</h2>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6">
            <input type="hidden" name="tab" value="office_hours">
            @if(request('doctor_id'))
                <input type="hidden" name="doctor_id" value="{{ request('doctor_id') }}">
            @endif
            <div class="flex gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Wyszukaj po nazwisku lekarza..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                    >
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">
                    Szukaj
                </button>
                @if(request('search') || request('doctor_id'))
                    <a href="{{ route('dashboard', ['tab' => 'office_hours']) }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium transition">
                        Wyczyść
                    </a>
                @endif
            </div>
        </form>

        @if(isset($officeHours) && $officeHours->count() > 0)
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lekarz</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dzień tygodnia</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Godziny</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($officeHours as $hour)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{ $hour->doctor->name }} {{ $hour->doctor->surname }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @switch($hour->day_of_week)
                                        @case(1) Poniedziałek @break
                                        @case(2) Wtorek @break
                                        @case(3) Środa @break
                                        @case(4) Czwartek @break
                                        @case(5) Piątek @break
                                        @case(6) Sobota @break
                                        @case(7) Niedziela @break
                                    @endswitch
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($hour->start_time && $hour->end_time)
                                        {{ \Carbon\Carbon::parse($hour->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($hour->end_time)->format('H:i') }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Zamknięte</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $officeHours->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                Nie znaleziono godzin otwarcia
            </div>
        @endif
    </div>
</div>
