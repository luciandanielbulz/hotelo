<x-layout>
    <!-- Session-Nachrichten als Toasts anzeigen -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session()->has('success'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('success') }}',
                        type: 'success'
                    }
                }));
            @endif

            @if(session()->has('error'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('error') }}',
                        type: 'error'
                    }
                }));
            @endif
        });
    </script>
    
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
                                class="w-full bg-blue-50 border border-blue-200 rounded-lg py-3 px-4 text-left hover:bg-blue-100 transition-all duration-200 shadow-sm hover:shadow-md">
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
                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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
                <!-- Einstellungen Button -->
                <a href="{{ route('invoiceupload.settings') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Einstellungen
                </a>
                
                <!-- ZIP Download Button -->
                <button
                    @click="open = true"
                    class="inline-flex items-center px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg hover:bg-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ZIP Download
                </button>
                
                <!-- Neu Button -->
                <a href="{{ route('invoiceupload.upload.create') }}" 
                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-semibold rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl">
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
