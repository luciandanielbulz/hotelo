@props([
    'availableYears' => []
])

<x-modal name="delete-bankdata-year" maxWidth="md">
    <form method="POST" action="{{ route('bankdata.delete-year') }}" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900 mb-4">
            Bankdaten nach Jahr löschen
        </h2>

        <p class="mt-1 text-sm text-gray-600 mb-6">
            Wählen Sie ein Jahr und den Typ (Einnahmen oder Ausgaben), der gelöscht werden soll. Diese Aktion kann nicht rückgängig gemacht werden.
        </p>

        <!-- Typ-Auswahl -->
        <div class="mb-4">
            <label for="delete_type" class="block text-sm font-medium text-gray-700 mb-2">
                Typ auswählen:
            </label>
            <select 
                id="delete_type" 
                name="type" 
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-700 sm:text-sm">
                <option value="">Bitte wählen...</option>
                <option value="income">Einnahmen</option>
                <option value="expense">Ausgaben</option>
            </select>
        </div>

        <!-- Jahr-Auswahl -->
        <div class="mb-6">
            <label for="delete_year" class="block text-sm font-medium text-gray-700 mb-2">
                Jahr auswählen:
            </label>
            <select 
                id="delete_year" 
                name="year" 
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-700 sm:text-sm">
                <option value="">Bitte wählen...</option>
                @foreach($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <!-- Warnung -->
        <div class="mb-6 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Warnung: Diese Aktion kann nicht rückgängig gemacht werden!
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Alle Bankdaten des ausgewählten Jahres und Typs werden dauerhaft gelöscht.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button 
                type="button"
                x-on:click="$dispatch('close-modal', 'delete-bankdata-year')"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                Abbrechen
            </button>
            <button 
                type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                Löschen
            </button>
        </div>
    </form>
</x-modal>

