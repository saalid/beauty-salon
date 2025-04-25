<x-filament::card>
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">گزارش تراکنس های موفق</h2>
        <a
            href="{{ route('transactions.export') }}"
            class="filament-button inline-flex items-center px-4 py-2 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-700"
        >
            دانلود اکسل
        </a>
    </div>
</x-filament::card>
