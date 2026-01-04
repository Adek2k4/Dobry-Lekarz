<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (auth()->user() && optional(auth()->user()->role)->name === 'patient')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <a href="{{ url('/specialists') }}" class="block bg-gray-100 dark:bg-gray-900 hover:shadow-md transition p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Znajdź specjalistę</h3>
                                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Przejdź do listy specjalistów.</p>
                            </a>

                            <a href="{{ url('/my-appointments') }}" class="block bg-gray-100 dark:bg-gray-900 hover:shadow-md transition p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Moje wizyty</h3>
                                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Zobacz swoje umówione wizyty.</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
