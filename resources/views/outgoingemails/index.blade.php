<x-layout>
    <!-- Moderner Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Versendete Objekte</h1>
            <p class="text-gray-600">Übersicht aller gesendeten Objekte</p>
        </div>
    </div>

    <!-- Livewire E-Mail-Tabelle -->
    <div>
        <livewire:outgoing-email.outgoing-email-table/>
    </div>
</x-layout>
