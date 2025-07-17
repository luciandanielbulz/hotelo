
<!-- Moderner Header -->
<div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h1 class="text-xl font-bold text-gray-900">Rechnungspositionen</h1>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <button type="button" 
                    wire:click="addPosition" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Position
            </button>
            <button type="button" 
                    wire:click="addTextPosition" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold rounded-lg hover:from-green-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Textposition
            </button>
        </div>
    </div>

    <!-- Moderne Tabelle -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sm:pl-6">Pos</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Menge</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Einheit</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Beschreibung</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Preis/EH</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gesamtpreis</th>
                        <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Aktionen</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="positionsTable">
                    @php $positionNumber = 1; @endphp
                    @forelse ($positions as $position)
                        <tr data-id="{{ $position->id }}" class="hover:bg-white/50 transition-colors duration-200">
                            @if ($position->positiontext == false)
                                <!-- Normale Position -->
                                <td class="py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                        {{ $positionNumber }}
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900 font-semibold">{{ number_format($position->amount, 2, ',', '.') }}</td>
                                <td class="px-3 py-4 text-sm text-gray-700 font-medium">{{ $position->unit_name }}</td>
                                <td class="px-3 py-4 text-sm text-gray-900 font-medium">{{ $position->designation }}</td>
                                <td class="px-3 py-4 text-sm text-gray-900 font-semibold">{{ number_format($position->price, 2, ',', '.') }} €</td>
                                <td class="px-3 py-4 text-sm text-gray-900 font-bold">{{ number_format($position->price * $position->amount, 2, ',', '.') }} €</td>
                                <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('invoiceposition.edit', $position->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-medium rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-md hover:shadow-lg text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Bearbeiten
                                        </a>
                                        <button type="button" 
                                                wire:click="deletePosition({{ $position->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-md hover:shadow-lg text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Löschen
                                        </button>
                                    </div>
                                </td>
                                @php $positionNumber++; @endphp
                            @else
                                <!-- Textposition -->
                                <td class="py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                </td>
                                <td colspan="4" class="px-3 py-4">
                                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center mb-2">
                                            <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Textposition</span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 whitespace-pre-line">{{ $position->details }}</div>
                                    </div>
                                </td>
                                <td></td>
                                <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('invoiceposition.edit', $position->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-medium rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-md hover:shadow-lg text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Bearbeiten
                                        </a>
                                        <button type="button" 
                                                wire:click="deletePosition({{ $position->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-md hover:shadow-lg text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Löschen
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Positionen vorhanden</h3>
                                    <p class="text-sm text-gray-500 mb-4">Fügen Sie die erste Position zu dieser Rechnung hinzu</p>
                                    <button type="button" 
                                            wire:click="addPosition" 
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Erste Position hinzufügen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

