<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-26">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center" style="margin-left: -60px;">
                    <a href="{{ url('/') }}" aria-label="Powrót do strony głównej - Dobry Lekarz" style="padding-top: 5px; padding-bottom: 5px;">
                        <x-application-logo class="block fill-current text-gray-800 dark:text-gray-200" style="height: 100px; width: auto;" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ms-10 sm:flex sm:items-center">
                    @auth
                        @if (Auth::user()->role && Auth::user()->role->name === 'admin')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Panel administratora') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets')">
                                {{ __('Zgłoszenia') }}
                            </x-nav-link>
                        @else
                            @if (Auth::user()->role && Auth::user()->role->name !== 'doctor')
                                <x-nav-link :href="url('/search')" :active="request()->is('search')">
                                    {{ __('Znajdź specjalistę') }}
                                </x-nav-link>
                            @endif
                            <x-nav-link :href="url('/my-appointments')" :active="request()->is('my-appointments')">
                                {{ __('Moje wizyty') }}
                            </x-nav-link>
                        @endif
                    @endauth
                    @guest
                    <x-nav-link :href="url('/register')" :active="request()->is('register')">
                        {{ __('Znajdź specjalistę') }}
                    </x-nav-link>
                    @endguest
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <div class="text-sm font-medium text-white">Profil</div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button aria-label="Menu użytkownika - {{ Auth::user()->name }}" 
                                aria-haspopup="true" 
                                class="inline-flex items-center px-3 py-0.5 rounded-md bg-slate-800 dark:bg-slate-900 text-gray-100 dark:text-gray-200 text-sm font-medium leading-5 hover:bg-slate-700 dark:hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Twój profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                {{ __('Wyloguj') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth
            @guest
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 dark:text-white bg-transparent hover:bg-gray-100 dark:hover:bg-gray-900 rounded-md">Zaloguj się</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Zarejestruj się</a>
                @endif
            </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                        aria-label="Menu nawigacji" 
                        aria-expanded="false" 
                        x-bind:aria-expanded="open.toString()"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->role && Auth::user()->role->name === 'admin')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Panel administratora') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets')">
                        {{ __('Zgłoszenia') }}
                    </x-responsive-nav-link>
                @else
                    @if (Auth::user()->role && Auth::user()->role->name !== 'doctor')
                        <x-responsive-nav-link :href="url('/search')" :active="request()->is('search')">
                            {{ __('Znajdź specjalistę') }}
                        </x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="url('/my-appointments')" :active="request()->is('my-appointments')">
                        {{ __('Moje wizyty') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>
        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-red-700 dark:focus:text-red-300 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
        @endauth
        @guest
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200">Zaloguj się</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-white bg-blue-600 rounded-md mt-2">Zarejestruj się</a>
                @endif
            </div>
        </div>
        @endguest
    </div>
</nav>
