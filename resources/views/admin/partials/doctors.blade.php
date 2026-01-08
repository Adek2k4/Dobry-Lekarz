<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Lekarze</h3>
        
        <!-- Search -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <input type="hidden" name="tab" value="doctors">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj lekarza..." class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Szukaj</button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Imię i nazwisko</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Specjalizacja</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Adres</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Akcje</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($doctors as $doctor)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $doctor->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $doctor->name }} {{ $doctor->surname }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $doctor->doctorData->specialization->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button onclick="revealSensitiveData(this, 'email', '{{ $doctor->email }}')" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Pokaż email
                                </button>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($doctor->phone)
                                    <button onclick="revealSensitiveData(this, 'telefon', '{{ $doctor->phone }}')" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                        Pokaż telefon
                                    </button>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                @if($doctor->doctorData && $doctor->doctorData->address)
                                    {{ $doctor->doctorData->address->street }} {{ $doctor->doctorData->address->house_number }}, {{ $doctor->doctorData->address->postal_code }} {{ $doctor->doctorData->address->city }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Brak adresu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard', ['tab' => 'reviews', 'user_id' => $doctor->id]) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                        Oceny
                                    </a>
                                    <a href="{{ route('dashboard', ['tab' => 'appointments', 'user_id' => $doctor->id]) }}" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">
                                        Wizyty
                                    </a>
                                    <a href="{{ route('dashboard', ['tab' => 'office_hours', 'doctor_id' => $doctor->id]) }}" class="px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded">
                                        Godziny
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Brak lekarzy</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($doctors->hasPages())
            <div class="mt-4">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>
