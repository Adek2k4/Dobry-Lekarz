<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Oceny</h3>
        
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <input type="hidden" name="tab" value="reviews">            @if(request('user_id'))
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po nazwisku..." class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                
                <select name="rating" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Wszystkie oceny</option>
                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>⭐⭐⭐</option>
                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>⭐⭐</option>
                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>⭐</option>
                </select>
                
                <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Sortuj</option>
                    <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>Data: od najnowszych</option>
                    <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Data: od najstarszych</option>
                    <option value="rating_desc" {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>Ocena: od najwyższych</option>
                    <option value="rating_asc" {{ request('sort') === 'rating_asc' ? 'selected' : '' }}>Ocena: od najniższych</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Filtruj</button>
            </div>
            @if(request('search') || request('rating') || request('user_id'))
                <div class="mt-2">
                    <a href="{{ route('dashboard', ['tab' => 'reviews']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Wyczyść filtry
                    </a>
                </div>
            @endif
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Lekarz</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pacjent</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ocena</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Komentarz</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reviews as $review)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $review->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $review->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $review->doctor->name }} {{ $review->doctor->surname }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $review->patient->name }} {{ $review->patient->surname }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="text-yellow-500">{{ str_repeat('⭐', $review->rating) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($review->content)
                                    <span class="text-gray-700 dark:text-gray-300">{{ Str::limit($review->content, 50) }}</span>
                                @else
                                    <span class="text-gray-400">Brak komentarza</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Brak ocen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
