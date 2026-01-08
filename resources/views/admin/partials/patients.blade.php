<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pacjenci</h3>
        
        <!-- Search -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <input type="hidden" name="tab" value="patients">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj pacjenta..." class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Akcje</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($patients as $patient)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $patient->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $patient->name }} {{ $patient->surname }}</td>
                            <td class="px-4 py-3 text-sm">
                                <button onclick="revealSensitiveData(this, 'email', '{{ $patient->email }}')" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Pokaż email
                                </button>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($patient->phone)
                                    <button onclick="revealSensitiveData(this, 'telefon', '{{ $patient->phone }}')" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                        Pokaż telefon
                                    </button>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('dashboard', ['tab' => 'reviews', 'user_id' => $patient->id]) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                        Oceny
                                    </a>
                                    <a href="{{ route('dashboard', ['tab' => 'appointments', 'user_id' => $patient->id]) }}" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">
                                        Wizyty
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Brak pacjentów</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($patients->hasPages())
            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>
