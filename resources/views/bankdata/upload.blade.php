<x-layout>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">JSON-Datei hochladen</h1>
                <p class="text-gray-600 mt-1">Importieren Sie Bankdaten aus einer JSON-Datei</p>
            </div>
            <div>
                <a href="{{ route('bankdata.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Zurück
                </a>
            </div>
        </div>
    </div>

    <!-- Formular Container -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 mb-6 border border-stone-200">
        <form action="{{ route('bankdata.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="json_file" class="block text-sm font-bold text-gray-800 mb-2">Wähle eine JSON-Datei:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <input class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700" type="file" name="json_file" id="json_file" accept=".json" required>
                    </div>
                </div>

                <div>
                    <label for="auto_detect_type" class="block text-sm font-bold text-gray-800 mb-2">Automatische Einnahmen/Ausgaben-Erkennung:</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="auto_detect_type" value="auto" checked class="mr-2 text-blue-900 focus:ring-blue-700">
                            <span class="text-sm text-gray-700">Automatisch (positiv = Einnahmen, negativ = Ausgaben)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="auto_detect_type" value="manual" class="mr-2 text-blue-900 focus:ring-blue-700">
                            <span class="text-sm text-gray-700">Manuell auswählen</span>
                        </label>
                    </div>
                </div>

                <div id="manual_type_selection" class="hidden">
                    <label for="default_type" class="block text-sm font-bold text-gray-800 mb-2">Standard-Typ für alle Transaktionen:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <select name="default_type" id="default_type" class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700">
                            <option value="expense">Ausgaben</option>
                            <option value="income">Einnahmen</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="overwrite_duplicates" value="1" class="mr-2 text-blue-900 focus:ring-blue-700">
                        <span class="text-sm text-gray-700">Bestehende Einträge überschreiben (Duplikate löschen)</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Aktivieren Sie diese Option, um bestehende Einträge mit gleicher Referenznummer zu überschreiben.</p>
                </div>

                <div class="flex justify-end pt-4">
                    <button class="inline-flex items-center px-6 py-3 bg-blue-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Hochladen
                    </button>
                </div>
            </div>
        </form>
    </div>

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
