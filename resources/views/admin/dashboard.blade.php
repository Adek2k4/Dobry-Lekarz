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

    @push('scripts')
    <script>
        function revealSensitiveData(element, dataType, dataValue) {
            if (confirm('Czy na pewno chcesz wyświetlić ' + dataType + '?')) {
                element.innerHTML = '<span class="text-gray-900 dark:text-gray-100">' + dataValue + '</span>';
                element.classList.remove('cursor-pointer', 'hover:bg-gray-100', 'dark:hover:bg-gray-600');
            }
        }

        function showModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function hideModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        async function loadUserAppointments(userId, userName) {
            const response = await fetch(`/admin/users/${userId}/appointments`);
            const data = await response.json();
            
            let html = `<h3 class="text-lg font-semibold mb-4">Wizyty - ${userName}</h3>`;
            
            if (data.appointments.length === 0) {
                html += '<p class="text-gray-500">Brak wizyt</p>';
            } else {
                html += '<div class="space-y-2">';
                data.appointments.forEach(apt => {
                    const date = new Date(apt.appointment_date).toLocaleString('pl-PL');
                    const otherPerson = apt.doctor ? `${apt.doctor.name} ${apt.doctor.surname}` : `${apt.patient.name} ${apt.patient.surname}`;
                    const statusClass = apt.status === 'completed' ? 'text-green-600' : apt.status === 'cancelled' ? 'text-red-600' : 'text-blue-600';
                    html += `<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded"><p><strong>Data:</strong> ${date}</p><p><strong>${apt.doctor ? 'Pacjent' : 'Lekarz'}:</strong> ${otherPerson}</p><p><strong>Status:</strong> <span class="${statusClass}">${apt.status}</span></p></div>`;
                });
                html += '</div>';
            }
            
            document.getElementById('modal-content').innerHTML = html;
            showModal('data-modal');
        }

        async function loadUserReviews(userId, userName) {
            const response = await fetch(`/admin/users/${userId}/reviews`);
            const data = await response.json();
            
            let html = `<h3 class="text-lg font-semibold mb-4">Oceny - ${userName}</h3>`;
            
            if (data.reviews.length === 0) {
                html += '<p class="text-gray-500">Brak ocen</p>';
            } else {
                html += '<div class="space-y-2">';
                data.reviews.forEach(review => {
                    const otherPerson = review.doctor ? `${review.doctor.name} ${review.doctor.surname}` : `${review.patient.name} ${review.patient.surname}`;
                    html += `<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded"><p><strong>${review.doctor ? 'Pacjent' : 'Lekarz'}:</strong> ${otherPerson}</p><p><strong>Ocena:</strong> ${'⭐'.repeat(review.rating)}</p>${review.content ? `<p><strong>Komentarz:</strong> ${review.content}</p>` : ''}</div>`;
                });
                html += '</div>';
            }
            
            document.getElementById('modal-content').innerHTML = html;
            showModal('data-modal');
        }

        async function loadOfficeHours(userId, doctorName) {
            const response = await fetch(`/admin/doctors/${userId}/office-hours`);
            const data = await response.json();
            
            const days = ['', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];
            
            let html = `<h3 class="text-lg font-semibold mb-4">Godziny otwarcia - ${doctorName}</h3>`;
            
            if (data.officeHours.length === 0) {
                html += '<p class="text-gray-500">Brak godzin otwarcia</p>';
            } else {
                html += '<div class="space-y-2">';
                data.officeHours.forEach(oh => {
                    html += `<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded"><p><strong>${days[oh.day_of_week]}:</strong> ${oh.start_time || 'Zamknięte'} - ${oh.end_time || 'Zamknięte'}</p></div>`;
                });
                html += '</div>';
            }
            
            document.getElementById('modal-content').innerHTML = html;
            showModal('data-modal');
        }

        async function loadDoctorAddress(userId, doctorName, addressId) {
            let html = `<h3 class="text-lg font-semibold mb-4">Adres gabinetu - ${doctorName}</h3>`;
            html += `<p>ID adresu: ${addressId}</p>`;
            document.getElementById('modal-content').innerHTML = html;
            showModal('data-modal');
        }
    </script>
    @endpush

    <!-- Modal -->
    <div id="data-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div id="modal-content" class="text-gray-900 dark:text-gray-100"></div>
            <div class="mt-6 flex justify-end">
                <button onclick="hideModal('data-modal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md">
                    Zamknij
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
