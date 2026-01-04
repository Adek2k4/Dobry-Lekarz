<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-transparent">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <div class="px-4 py-6 md:col-span-2">
                            <h1 class="text-6xl font-extrabold text-gray-900 dark:text-gray-100 text-center md:text-left">Dobry Lekarz - Twoje zdrowie w dobrych rękach</h1>
                            <p class="mt-6 text-3xl text-gray-700 dark:text-gray-300 text-left">Szybko znajdź zaufanych specjalistów w Twojej okolicy, umów wizytę online i zarządzaj swoimi terminami w jednym miejscu. Nasza platforma pomaga dobrać lekarza według specjalizacji, lokalizacji i opinii pacjentów.</p>
                            <p class="mt-4 text-3xl text-gray-700 dark:text-gray-300 text-left">Dołącz już dziś i korzystaj z wygody elektronicznego umawiania wizyt oraz bezpiecznego profilu pacjenta.</p>
                        </div>
                        @auth
                        <div class="flex items-start justify-center md:justify-end md:col-span-1">
                            <a href="{{ url('/search') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-md self-start" style="margin-top:200px;">Znajdź specjalistę</a>
                        </div>
                        @endauth
                        @guest
                        <div class="flex items-start justify-center md:justify-end md:col-span-1">
                            <a href="{{ url('/register') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-md self-start" style="margin-top:200px;">Znajdź specjalistę</a>
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
