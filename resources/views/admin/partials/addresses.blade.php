<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Adresy</h2>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6">
            <input type="hidden" name="tab" value="addresses">
            <div class="flex gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Wyszukaj po mieście, ulicy lub kodzie pocztowym..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                    >
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">
                    Szukaj
                </button>
                @if(request('search'))
                    <a href="{{ route('dashboard', ['tab' => 'addresses']) }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium transition">
                        Wyczyść
                    </a>
                @endif
            </div>
        </form>

        @if(isset($addresses) && $addresses->count() > 0)
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Miasto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kod pocztowy</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ulica</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Numer domu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pełny adres</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($addresses as $address)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->city }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->postal_code }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->street }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->house_number }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $address->street }} {{ $address->house_number }}, {{ $address->postal_code }} {{ $address->city }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $addresses->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                Nie znaleziono adresów
            </div>
        @endif
    </div>
</div>
