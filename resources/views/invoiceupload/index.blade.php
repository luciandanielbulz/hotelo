<x-layout>
    <!-- Alpine.js-Scope um den gesamten relevanten Bereich erweitert -->
    <div x-data="{ open: false }" x-cloak>
        <!-- Modernes Modal für ZIP-Downloads -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50"
            style="display: none;">
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white/90 backdrop-blur-lg p-6 rounded-xl shadow-2xl border border-white/20 w-96 max-w-md mx-4">
                
                <!-- Modal Header -->
                <div class="flex items-center mb-6">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">ZIP-Download wählen</h3>
                </div>
                
                <p class="text-gray-600 mb-4">Wählen Sie einen Monat für den ZIP-Download aus:</p>
                
                <!-- Monatsliste -->
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @if($months->isEmpty())
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">Keine Monate verfügbar</p>
                        </div>
                    @else
                        @foreach($months as $month)
                            <button
                                @click="window.location.href='{{ route('invoiceupload.downloadZipForMonth', ['month' => $month]) }}'"
                                class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg py-3 px-4 text-left hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900">{{ $month }}</span>
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    @endif
                </div>
                
                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end">
                    <button
                        @click="open = false"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Schließen
                    </button>
                </div>
            </div>
        </div>

        <!-- Moderner Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ausgabenverwaltung</h1>
                <p class="text-gray-600">Verwalten Sie alle Ihre hochgeladenen Belege und Rechnungen</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <!-- ZIP Download Button -->
                <button
                    @click="open = true"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ZIP Download
                </button>
                
                <!-- Neu Button -->
                <a href="{{ route('invoiceupload.upload.create') }}" 
                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Neue Rechnung
                </a>
            </div>
        </div>

        <!-- Livewire-Tabelle -->
        <div>
            <livewire:invoice-upload.invoice-upload-table />
        </div>
    </div>
</x-layout>
