@props(['status'])

@if ($status === 'scheduled')
    <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-white">
        Zaplanowana
    </span>
@elseif ($status === 'completed')
    <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-white">
        Zakończona
    </span>
@elseif ($status === 'cancelled')
    <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-white">
        Anulowana
    </span>
@endif
