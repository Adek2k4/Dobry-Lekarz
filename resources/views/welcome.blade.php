<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-transparent">
                <article class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <div class="px-4 py-6 md:col-span-2">
                            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-gray-100 text-center md:text-left">Dobry Lekarz - Twoje zdrowie w dobrych rękach</h1>
                            <p class="mt-6 text-xl sm:text-2xl md:text-3xl text-gray-700 dark:text-gray-300 text-left leading-relaxed">Szybko znajdź zaufanych specjalistów w Twojej okolicy, umów wizytę online i zarządzaj swoimi terminami w jednym miejscu. Nasza platforma pomaga dobrać lekarza według specjalizacji, lokalizacji i opinii pacjentów.</p>
                            <p class="mt-4 text-xl sm:text-2xl md:text-3xl text-gray-700 dark:text-gray-300 text-left leading-relaxed">Dołącz już dziś i korzystaj z wygody elektronicznego umawiania wizyt oraz bezpiecznego profilu pacjenta.</p>
                        </div>
                        @auth
                        <div class="flex items-start justify-center md:justify-end md:col-span-1 mt-8 md:mt-48">
                            <a href="{{ url('/search') }}" 
                               aria-label="Znajdź specjalistę - przejdź do wyszukiwarki lekarzy"
                               class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 text-white text-lg font-semibold rounded-md self-start transition-colors duration-200">Znajdź specjalistę</a>
                        </div>
                        @endauth
                        @guest
                        <div class="flex items-start justify-center md:justify-end md:col-span-1 mt-8 md:mt-48">
                            <a href="{{ url('/register') }}" 
                               aria-label="Znajdź specjalistę - zarejestruj się, aby umówić wizytę"
                               class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 text-white text-lg font-semibold rounded-md self-start transition-colors duration-200">Znajdź specjalistę</a>
                        </div>
                        @endguest
                    </div>
                </article>
            </div>
        </div>
    </div>
</x-app-layout>
