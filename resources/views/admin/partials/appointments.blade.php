<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Wizyty</h3>
        
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <input type="hidden" name="tab" value="appointments">            @if(request('user_id'))
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po nazwisku..." class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Wszystkie statusy</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Zaplanowana</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Zakończona</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Anulowana</option>
                </select>
                
                <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Sortuj</option>
                    <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>Data: od najnowszych</option>
                    <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Data: od najstarszych</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Filtruj</button>
            </div>
            @if(request('search') || request('status') || request('user_id'))
                <div class="mt-2">
                    <a href="{{ route('dashboard', ['tab' => 'appointments']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Wyczyść filtry
                    </a>
                </div>
            @endif
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data i godzina</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Lekarz</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pacjent</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Powód</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($appointments as $appointment)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $appointment->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $appointment->doctor->name }} {{ $appointment->doctor->surname }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $appointment->patient->name }} {{ $appointment->patient->surname }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($appointment->status === 'scheduled')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded text-xs">Zaplanowana</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs">Zakończona</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded text-xs">Anulowana</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($appointment->reason)
                                    <button onclick="revealSensitiveData(this, 'powód wizyty', '{{ addslashes($appointment->reason) }}')" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                        Pokaż powód
                                    </button>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Brak wizyt</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
