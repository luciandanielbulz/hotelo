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
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-xs mr-3">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input type="number" step="0.01" name="amount" label="Menge" value="{{ old('amount', $offerpositioncontent->amount) }}" placeholder="Geben Sie die Menge ein" />
                    <x-dropdown_body name="unit_id" label="Einheit" :options="$units->pluck('unitdesignation', 'id')" selected="{{ old('unit_id', $offerpositioncontent->unit_id) }}" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input type="number" step="0.01" name="price" label="Preis/EH" value="{{ old('price', $offerpositioncontent->price) }}" placeholder="Preis eingeben" />
                    <x-input name="sequence" label="Reihenfolge" value="{{ old('sequence', $offerpositioncontent->sequence) }}" placeholder="Reihenfolge eingeben" />
                </div>

                <div>
                    <x-input type="text" name="designation" label="Beschreibung" value="{{ old('designation', $offerpositioncontent->designation) }}" placeholder="Beschreibung eingeben" />
                </div>

                <div>
                    <label for="details" class="block text-sm font-bold text-gray-800 mb-2">Positionsdetail</label>
                    <textarea name="details" id="details" rows="8" class="block w-full rounded-lg bg-white/70 backdrop-blur-sm px-4 py-3 text-base text-gray-900 border border-gray-300 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-md hover:shadow-lg transition-all duration-200" placeholder="Detaillierte Beschreibung der Position...">{{ old('details', $offerpositioncontent->details) }}</textarea>
                </div>

                <!-- Speichern Button -->
                <div class="flex justify-center md:justify-start pt-4">
                    <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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
                
                <div class="md:w-1/3">
                    <x-input name="sequence" label="Reihenfolge" value="{{ $offerpositioncontent->sequence }}" placeholder="Reihenfolge eingeben" />
                </div>

                <div>
                    <label for="details" class="block text-sm font-bold text-gray-800 mb-2">Positionstext</label>
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
</x-layout>
