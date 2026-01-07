<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Währungen</h1>
            <p class="mt-2 text-sm text-gray-700">Verwalten Sie die verfügbaren Währungen für Ihr Unternehmen.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('currencies.create') }}" 
               class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                + Neu
            </a>
        </div>
    </div>

    <div class="mt-8">
        <livewire:currency.currency-table />
    </div>
</x-layout> 