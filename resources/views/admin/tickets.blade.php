<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Zgłoszenia lekarzy') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Zgłoszenia</h3>
                    
                    <!-- Search -->
                    <form method="GET" action="{{ route('admin.tickets') }}" class="mb-6">
                        <div class="flex gap-4">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Wyszukaj po nazwisku lekarza lub pacjenta..."
                                class="flex-1 px-4 py-2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                            >
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">
                                Szukaj
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.tickets') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium transition">
                                    Wyczyść
                                </a>
                            @endif
                        </div>
                    </form>

                    @if($tickets->count() > 0)
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Zgłoszony lekarz</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Zgłaszający</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Powód</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $ticket->id }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                <div>
                                                    <div class="font-medium">{{ $ticket->doctor->name }} {{ $ticket->doctor->surname }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->doctor->email }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $ticket->patient->name }} {{ $ticket->patient->surname }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    {{ $ticket->ticketType->description }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $ticket->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <form method="POST" action="{{ route('admin.ticket.delete', $ticket->id) }}" class="inline" onsubmit="return confirm('Czy na pewno chcesz usunąć to zgłoszenie?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium transition">
                                                        Usuń zgłoszenie
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            Nie znaleziono zgłoszeń
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
