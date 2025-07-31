<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">JSON-Datei hochladen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte aktualisieren Sie die Informationen des Klienten.</p>
        </div>

    <form action="{{ route('bankdata.upload') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
        @csrf
        <div class="sm:col-span-6 space-y-6">
            <div>
                <label for="json_file" class="block text-sm font-bold text-gray-800 mb-2">Wähle eine JSON-Datei:</label>
                <input class="block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200" type="file" name="json_file" id="json_file" accept=".json" required>
            </div>

            <div>
                <label for="auto_detect_type" class="block text-sm font-bold text-gray-800 mb-2">Automatische Einnahmen/Ausgaben-Erkennung:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="auto_detect_type" value="auto" checked class="mr-2 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Automatisch (positiv = Einnahmen, negativ = Ausgaben)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="auto_detect_type" value="manual" class="mr-2 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Manuell auswählen</span>
                    </label>
                </div>
            </div>

            <div id="manual_type_selection" class="hidden">
                <label for="default_type" class="block text-sm font-bold text-gray-800 mb-2">Standard-Typ für alle Transaktionen:</label>
                <select name="default_type" id="default_type" class="block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                    <option value="expense">Ausgaben</option>
                    <option value="income">Einnahmen</option>
                </select>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="overwrite_duplicates" value="1" class="mr-2 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Bestehende Einträge überschreiben (Duplikate löschen)</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Aktivieren Sie diese Option, um bestehende Einträge mit gleicher Referenznummer zu überschreiben.</p>
            </div>

            <button class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-xl transition-all duration-300" type="submit">Hochladen</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const autoDetectRadios = document.querySelectorAll('input[name="auto_detect_type"]');
            const manualSelection = document.getElementById('manual_type_selection');

            autoDetectRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'manual') {
                        manualSelection.classList.remove('hidden');
                    } else {
                        manualSelection.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-layout>
