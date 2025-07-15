<x-layout>
    <!-- Moderner Header wie bei den Kunden -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Angebote</h1>
            <p class="text-gray-600">Verwalten Sie alle Ihre Geschäftsangebote</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('customer.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Neues Angebot
            </a>
        </div>
    </div>

    <!-- Livewire Angebots-Tabelle -->
    <div>
        <livewire:offer.positiontable />
    </div>
</x-layout>
