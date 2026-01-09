<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Administratora') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6">
                <!-- Sidebar -->
                <div class="w-56 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                        <nav class="flex flex-col">
                            <a href="{{ route('dashboard', ['tab' => 'patients']) }}" 
                               class="px-4 py-3 text-sm font-medium border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'patients' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Pacjenci
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'doctors']) }}" 
                               class="px-4 py-3 text-sm font-medium border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'doctors' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Lekarze
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'appointments']) }}" 
                               class="px-4 py-3 text-sm font-medium border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'appointments' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Wizyty
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'reviews']) }}" 
                               class="px-4 py-3 text-sm font-medium border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'reviews' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Oceny
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'office_hours']) }}" 
                               class="px-4 py-3 text-sm font-medium border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'office_hours' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Godziny otwarcia
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'addresses']) }}" 
                               class="px-4 py-3 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $currentTab === 'addresses' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                Adresy
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1">
                    @if($currentTab === 'patients')
                        @include('admin.partials.patients')
                    @elseif($currentTab === 'doctors')
                        @include('admin.partials.doctors')
                    @elseif($currentTab === 'appointments')
                        @include('admin.partials.appointments')
                    @elseif($currentTab === 'reviews')
                        @include('admin.partials.reviews')
                    @elseif($currentTab === 'office_hours')
                        @include('admin.partials.office-hours')
                    @elseif($currentTab === 'addresses')
                        @include('admin.partials.addresses')
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
