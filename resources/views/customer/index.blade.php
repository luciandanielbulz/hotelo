<x-layout>
    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Kunden</h1>
            <p class="text-gray-600 mt-1">Verwalten Sie alle Ihre Geschäftskunden</p>
        </div>
        
        <!-- Desktop Header - mit Button -->
        <div class="hidden md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kunden</h1>
                <p class="text-gray-600">Verwalten Sie alle Ihre Geschäftskunden</p>
            </div>
            <div>
                <a href="{{ route('customer.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Neuer Kunde
                </a>
            </div>
        </div>
    </div>

    <!-- Livewire Kunden-Tabelle -->
    <div>
        <livewire:customer.customer-table />
    </div>

    <!-- Floating Action Button - nur auf Smartphones -->
    <div class="md:hidden fixed bottom-6 right-6 z-50">
        <a href="{{ route('customer.create') }}" 
           class="flex items-center justify-center w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </a>
    </div>
</x-layout>
