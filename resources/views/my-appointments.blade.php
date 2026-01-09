<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ $isDoctor ? __('Moje wizyty - Panel lekarza') : __('Moje wizyty') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if ($appointments->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Brak wizyt</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $isDoctor ? 'Nie masz jeszcze żadnych umówionych wizyt pacjentów.' : 'Nie masz jeszcze żadnych umówionych wizyt.' }}
                            </p>
                            @if (!$isDoctor)
                                <div class="mt-6">
                                    <a href="{{ url('/search') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow transition">
                                        Znajdź specjalistę
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Appointments Table -->
                        <div class="w-full">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                            Data i godzina
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                            {{ $isDoctor ? 'Pacjent' : 'Lekarz' }}
                                        </th>
                                        @if (!$isDoctor)
                                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                                Specjalizacja
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                                Adres
                                            </th>
                                        @endif
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                            Powód wizyty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                            Akcje
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($appointments as $appointment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-base">
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }}</div>
                                                <div class="text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-base">
                                                @if ($isDoctor)
                                                    <div class="text-base font-medium">
                                                        {{ $appointment->patient->name }} {{ $appointment->patient->surname }}
                                                    </div>
                                                    <div class="text-base text-gray-800 dark:text-white">
                                                        {{ $appointment->patient->email }}
                                                    </div>
                                                    @if ($appointment->patient->phone)
                                                        <div class="text-base text-gray-800 dark:text-white">
                                                            {{ $appointment->patient->phone }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <a href="{{ url('/' . Str::slug($appointment->doctor->name . ' ' . $appointment->doctor->surname) . '-' . $appointment->doctor->id) }}" class="text-base font-medium text-gray-800 dark:text-white underline hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $appointment->doctor->name }} {{ $appointment->doctor->surname }}
                                                    </a>
                                                @endif
                                            </td>
                                            @if (!$isDoctor)
                                                <td class="px-6 py-4 whitespace-nowrap text-base">
                                                    @if ($appointment->doctor->doctorData && $appointment->doctor->doctorData->specialization)
                                                        <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-white rounded text-sm">
                                                            {{ $appointment->doctor->doctorData->specialization->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-base">
                                                    @if ($appointment->doctor->doctorData && $appointment->doctor->doctorData->address)
                                                        <div>{{ $appointment->doctor->doctorData->address->street }} {{ $appointment->doctor->doctorData->address->house_number }}</div>
                                                        <div class="text-gray-800 dark:text-white">{{ $appointment->doctor->doctorData->address->postal_code }} {{ $appointment->doctor->doctorData->address->city }}</div>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <x-appointment-status :status="$appointment->status" />
                                            </td>
                                            <td class="px-6 py-4 text-base">
                                                @if ($appointment->reason)
                                                    <div class="max-w-xs truncate" title="{{ $appointment->reason }}">
                                                        {{ $appointment->reason }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-base">
                                                @if ($isDoctor)
                                                    @if ($appointment->status === 'scheduled')
                                                        <div class="flex space-x-2">
                                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Czy na pewno chcesz oznaczyć tę wizytę jako zakończoną? Ta akcja jest nieodwracalna.');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition">
                                                                    Zakończ
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Czy na pewno chcesz anulować tę wizytę? Ta akcja jest nieodwracalna.');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition">
                                                                    Anuluj
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">—</span>
                                                    @endif
                                                @else
                                                    @if ($appointment->status === 'completed')
                                                        @if (in_array($appointment->doctor_id, $reviewedDoctors))
                                                            <a href="{{ route('reviews.edit', ['appointment' => $appointment->id]) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded transition">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                </svg>
                                                                Edytuj ocenę
                                                            </a>
                                                        @else
                                                            <a href="{{ route('reviews.create', ['appointment' => $appointment->id]) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-gray-900 text-sm rounded transition">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                                Oceń lekarza
                                                            </a>
                                                        @endif
                                                    @elseif ($appointment->status === 'scheduled')
                                                        <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Czy na pewno chcesz anulować tę wizytę? Ta akcja jest nieodwracalna.');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                Anuluj wizytę
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400 text-xs">—</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
