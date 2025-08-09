<x-layout>
    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Archivierte Angebote</h1>
            <p class="text-gray-600 mt-1">Verwalten Sie alle Ihre archivierten Geschäftsangebote</p>
        </div>
        
        <!-- Desktop Header - ohne Buttons -->
        <div class="hidden md:block">
            <h1 class="text-2xl font-bold text-gray-900">Archivierte Angebote</h1>
            <p class="text-gray-600">Verwalten Sie alle Ihre archivierten Geschäftsangebote</p>
        </div>
    </div>

    <!-- Livewire Archivierte Angebots-Tabelle -->
    <div>
        <livewire:offer.archived-positiontable />
    </div>
</x-layout>
