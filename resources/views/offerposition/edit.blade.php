<x-layout>
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Position bearbeiten</h1>
            <p class="text-gray-600 mt-1">
                @if ($offerpositioncontent->positiontext == 0)
                    {{ $offerpositioncontent->designation }}
                @else
                    Textposition
                @endif
            </p>
        </div>
        
        <!-- Desktop Header - mit Button -->
        <div class="hidden md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Position bearbeiten</h1>
                <p class="text-gray-600">
                    @if ($offerpositioncontent->positiontext == 0)
                        {{ $offerpositioncontent->designation }}
                    @else
                        Textposition
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('offer.edit', ['offer' => $offerpositioncontent->offer_id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Zurück zum Angebot
                </a>
            </div>
        </div>
    </div>
    <!-- Moderne Form-Karte für normale Position -->
    @if ($offerpositioncontent->positiontext == 0)
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xs mr-3">
                        {{ $offerpositioncontent->sequence }}
                    </div>
                    Position bearbeiten
                </h2>
                <p class="text-sm text-gray-600 mt-1">Bearbeiten Sie die Positionsdetails</p>
            </div>

            <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Mobile: Vertikales Layout, Desktop: Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-input type="number" step="0.01" name="amount" label="Menge" value="{{ old('amount', $offerpositioncontent->amount) }}" placeholder="Geben Sie die Menge ein" />
                    <x-dropdown_body name="unit_id" label="Einheit" :options="$units->pluck('unitdesignation', 'id')" selected="{{ old('unit_id', $offerpositioncontent->unit_id) }}" />
                    <x-input type="number" step="0.01" name="price" label="Preis/EH" value="{{ old('price', $offerpositioncontent->price) }}" placeholder="Preis eingeben" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="designation" class="block text-sm font-bold text-gray-800">Beschreibung</label>
                        @if(auth()->user()->hasPermission('use_translation'))
                            <button type="button" onclick="openTranslationModal('designation')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                Übersetzen
                            </button>
                        @endif
                    </div>
                    <x-input type="text" name="designation" id="designation" value="{{ old('designation', $offerpositioncontent->designation) }}" placeholder="Beschreibung eingeben" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="details" class="block text-sm font-bold text-gray-800">Positionsdetail</label>
                        @if(auth()->user()->hasPermission('use_translation'))
                            <button type="button" onclick="openTranslationModal('details')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                Übersetzen
                            </button>
                        @endif
                    </div>
                    <textarea name="details" id="details" rows="8" class="block w-full rounded-lg bg-white/70 backdrop-blur-sm px-4 py-3 text-base text-gray-900 border border-gray-300 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-md hover:shadow-lg transition-all duration-200" placeholder="Detaillierte Beschreibung der Position...">{{ old('details', $offerpositioncontent->details) }}</textarea>
                </div>

                <!-- Speichern Button -->
                <div class="flex justify-center md:justify-start pt-4">
                    <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- Moderne Form-Karte für Textposition -->
        <div class="bg-green-50/60 backdrop-blur-lg rounded-xl p-6 border border-green-200 shadow-lg">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-green-700 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center text-white mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    Textposition bearbeiten
                </h2>
                <p class="text-sm text-green-600 mt-1">Bearbeiten Sie den Beschreibungstext</p>
            </div>

            <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="details" class="block text-sm font-bold text-gray-800">Positionstext</label>
                        @if(auth()->user()->hasPermission('use_translation'))
                            <button type="button" onclick="openTranslationModal('details')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                Übersetzen
                            </button>
                        @endif
                    </div>
                    <textarea name="details" id="details" rows="10" class="block w-full rounded-lg bg-white/70 backdrop-blur-sm px-4 py-3 text-base text-gray-900 border border-green-300 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-md hover:shadow-lg transition-all duration-200" placeholder="Geben Sie den Beschreibungstext ein..." required>{{ $offerpositioncontent->details }}</textarea>
                </div>

                <!-- Speichern Button -->
                <div class="flex justify-center md:justify-start pt-4">
                    <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                    <input type="hidden" name="amount" value="{{ $offerpositioncontent->amount }}">
                    <input type="hidden" name="unit_id" value="{{ $offerpositioncontent->unit_id }}">
                    <input type="hidden" name="designation" value="{{ $offerpositioncontent->designation }}">
                    <input type="hidden" name="price" value="{{ $offerpositioncontent->price }}">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold rounded-lg hover:from-green-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Floating Action Button - nur auf Smartphones -->
    <div class="md:hidden fixed bottom-6 right-6 z-50">
        <a href="{{ route('offer.edit', ['offer' => $offerpositioncontent->offer_id]) }}" 
           class="flex items-center justify-center w-14 h-14 bg-white/70 backdrop-blur-sm text-gray-700 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110 border border-gray-300"
           title="Zurück zum Angebot">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
            </svg>
        </a>
    </div>

    <!-- Übersetzungs-Modal -->
    <div id="translationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Text übersetzen</h3>
                    <button onclick="closeTranslationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label for="sourceText" class="block text-sm font-medium text-gray-700 mb-2">Text eingeben:</label>
                    <textarea id="sourceText" rows="6" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Geben Sie den zu übersetzenden Text ein..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="targetLanguage" class="block text-sm font-medium text-gray-700 mb-2">Zielsprache:</label>
                    <select id="targetLanguage" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Deutsch">Deutsch</option>
                        <option value="Englisch">Englisch</option>
                        <option value="Französisch">Französisch</option>
                        <option value="Spanisch">Spanisch</option>
                        <option value="Italienisch">Italienisch</option>
                    </select>
                </div>

                <div id="translationError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
                <div id="translationLoading" class="hidden mb-4 text-center">
                    <div class="inline-flex items-center text-blue-600">
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Übersetzung wird durchgeführt...
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="translateText()" class="flex-1 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Übersetzen
                    </button>
                    <button onclick="closeTranslationModal()" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                        Abbrechen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentFieldId = null;

        function openTranslationModal(fieldId) {
            currentFieldId = fieldId;
            const modal = document.getElementById('translationModal');
            const sourceText = document.getElementById('sourceText');
            
            // Aktuellen Feldwert als Vorschlag einfügen (falls vorhanden)
            const currentField = document.getElementById(fieldId);
            if (currentField && currentField.value) {
                sourceText.value = currentField.value;
            } else {
                sourceText.value = '';
            }
            
            // Fehler und Loading zurücksetzen
            document.getElementById('translationError').classList.add('hidden');
            document.getElementById('translationLoading').classList.add('hidden');
            
            modal.classList.remove('hidden');
            sourceText.focus();
        }

        function closeTranslationModal() {
            const modal = document.getElementById('translationModal');
            modal.classList.add('hidden');
            currentFieldId = null;
        }

        async function translateText() {
            const sourceText = document.getElementById('sourceText').value.trim();
            const targetLanguage = document.getElementById('targetLanguage').value;
            const errorDiv = document.getElementById('translationError');
            const loadingDiv = document.getElementById('translationLoading');

            if (!sourceText) {
                errorDiv.textContent = 'Bitte geben Sie einen Text ein.';
                errorDiv.classList.remove('hidden');
                return;
            }

            // Loading anzeigen
            errorDiv.classList.add('hidden');
            loadingDiv.classList.remove('hidden');

            try {
                const response = await fetch('{{ route("api.translate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        text: sourceText,
                        target_language: targetLanguage
                    })
                });

                const data = await response.json();
                loadingDiv.classList.add('hidden');

                if (data.success && data.translated_text) {
                    // Übersetzung in das entsprechende Feld einfügen
                    const targetField = document.getElementById(currentFieldId);
                    if (targetField) {
                        targetField.value = data.translated_text;
                        // Event auslösen, damit andere Scripts reagieren können
                        targetField.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    closeTranslationModal();
                } else {
                    errorDiv.textContent = data.message || 'Fehler bei der Übersetzung.';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                loadingDiv.classList.add('hidden');
                errorDiv.textContent = 'Fehler bei der Verbindung zum Server: ' + error.message;
                errorDiv.classList.remove('hidden');
            }
        }

        // Modal mit ESC schließen
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTranslationModal();
            }
        });

        // Modal schließen bei Klick außerhalb
        document.getElementById('translationModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeTranslationModal();
            }
        });
    </script>
</x-layout>
