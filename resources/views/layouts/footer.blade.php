<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
        <div class="text-sm">&copy; {{ date('Y') }} Dobry Lekarz. Wszelkie prawa zastrzeżone.</div>

        <div class="mt-3 md:mt-0 flex space-x-4">
            <a href="{{ url('/') }}" class="text-sm hover:underline">O nas</a>
            <a href="{{ url('/') }}" class="text-sm hover:underline">Kontakt</a>
            <a href="{{ url('/') }}" class="text-sm hover:underline">Polityka prywatności</a>
        </div>
    </div>
</footer>
