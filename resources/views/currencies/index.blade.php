<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Währungen</h1>
            <p class="mt-2 text-sm text-gray-700">Verwalten Sie die verfügbaren Währungen für Ihr Unternehmen.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('currencies.create') }}" 
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Neu
            </a>
        </div>
    </div>

    <div class="mt-8">
        <livewire:currency.currency-table />
    </div>
</x-layout> 