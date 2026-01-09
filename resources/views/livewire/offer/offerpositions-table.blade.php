<div class="space-y-4">
    <!-- Header mit Action-Buttons -->
    <!-- Mobile Header - zentriert -->
    <div class="md:hidden text-center">
        <h3 class="text-base font-semibold text-gray-900 mb-3">Positionen verwalten</h3>
        <div class="flex flex-col space-y-2">
            <button type="button" wire:click="addPosition" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Position hinzufügen
            </button>
            <button type="button" wire:click="addTextPosition" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Textposition hinzufügen
            </button>
        </div>
    </div>
    
    <!-- Desktop Header -->
    <div class="hidden md:flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900">Positionen verwalten</h3>
        <div class="flex space-x-3">
            <button type="button" wire:click="addPosition" 
                    class="inline-flex items-center px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Position
            </button>
            <button type="button" wire:click="addTextPosition" 
                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Textposition
            </button>
        </div>
    </div>

    <!-- Kartenansicht für Mobile -->
    <div class="md:hidden space-y-3">
        @php $positionNumber = 1; @endphp
        @forelse ($positions as $position)
            @if ($position->positiontext == false)
                <!-- Normale Position Karte -->
                <div class="bg-white/70 backdrop-blur-sm rounded-xl p-4 border border-white/20 shadow-md">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                {{ $positionNumber }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $position->designation }}</h4>
                                <p class="text-xs text-gray-500">Position {{ $positionNumber }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">{{ number_format($position->price * $position->amount, 2, ',', '.') }} €</div>
                            <div class="text-xs text-gray-500">Gesamt</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Menge</div>
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($position->amount, 2, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Einheit</div>
                            <div class="text-sm font-medium text-gray-700">{{ $position->unit_name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Preis/EH</div>
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($position->price, 2, ',', '.') }} €</div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('offerposition.edit', $position->id) }}" 
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-900 text-white font-medium rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Bearbeiten
                        </a>
                        <button type="button" 
                                wire:click="deletePosition({{ $position->id }})" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Löschen
                        </button>
                    </div>
                </div>
                @php $positionNumber++; @endphp
            @else
                <!-- Textposition Karte -->
                <div class="bg-green-50/70 backdrop-blur-sm rounded-xl p-4 border border-green-200 shadow-md">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-700 text-sm">Textposition</h4>
                                <p class="text-xs text-green-600">Beschreibungstext</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="text-sm text-gray-900 whitespace-pre-line bg-white/70 rounded-lg p-3 border border-green-200">{{ $position->details }}</div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('offerposition.edit', $position->id) }}" 
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-900 text-white font-medium rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Bearbeiten
                        </a>
                        <button wire:click="deletePosition({{ $position->id }})" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Löschen
                        </button>
                    </div>
                </div>
            @endif
        @empty
            <div class="bg-white/70 backdrop-blur-sm rounded-xl p-8 border border-white/20 shadow-md text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Keine Positionen vorhanden</h3>
                <p class="text-sm text-gray-500">Fügen Sie Positionen hinzu, um das Angebot zu vervollständigen.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Tabellenansicht -->
    <div class="hidden md:block overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
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
                <tbody class="divide-y divide-gray-100" id="positionsTable">
                    @php $positionNumber = 1; @endphp
                    @forelse ($positions as $position)
                        <tr data-id="{{ $position->id }}" class="hover:bg-white/50 transition-colors duration-200">
                            @if ($position->positiontext == false)
                                <!-- Normale Position -->
                                <td class="py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">
                                    <div class="w-8 h-8 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-xs">
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
                                        <!-- Pfeil-Buttons (nur LG) -->
                                        <div class="hidden lg:flex items-center space-x-1">
                                            @php
                                                $currentIndex = $loop->index;
                                                $totalPositions = count($positions);
                                                $canMoveUp = $currentIndex > 0;
                                                $canMoveDown = $currentIndex < $totalPositions - 1;
                                            @endphp
                                            <button type="button" 
                                                    wire:click="movePositionUp({{ $position->id }})"
                                                    @if(!$canMoveUp) disabled @endif
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                    title="Nach oben">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <button type="button" 
                                                    wire:click="movePositionDown({{ $position->id }})"
                                                    @if(!$canMoveDown) disabled @endif
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                    title="Nach unten">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <a href="{{ route('offerposition.edit', $position->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-900 text-white font-medium rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Bearbeiten
                                        </a>
                                        <button type="button" 
                                                wire:click="deletePosition({{ $position->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
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
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold text-xs">
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
                                        <!-- Pfeil-Buttons (nur LG) -->
                                        <div class="hidden lg:flex items-center space-x-1">
                                            @php
                                                $currentIndex = $loop->index;
                                                $totalPositions = count($positions);
                                                $canMoveUp = $currentIndex > 0;
                                                $canMoveDown = $currentIndex < $totalPositions - 1;
                                            @endphp
                                            <button type="button" 
                                                    wire:click="movePositionUp({{ $position->id }})"
                                                    @if(!$canMoveUp) disabled @endif
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                    title="Nach oben">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <button type="button" 
                                                    wire:click="movePositionDown({{ $position->id }})"
                                                    @if(!$canMoveDown) disabled @endif
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                                    title="Nach unten">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <a href="{{ route('offerposition.edit', $position->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-900 text-white font-medium rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Bearbeiten
                                        </a>
                                        <button wire:click="deletePosition({{ $position->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-400 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 text-xs">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Keine Positionen vorhanden</h3>
                                <p class="text-sm text-gray-500">Fügen Sie Positionen hinzu, um das Angebot zu vervollständigen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
