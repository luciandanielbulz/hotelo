<div>
    <!-- Erweiterte Such- und Filter-Sektion -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 mb-6 border border-white/20 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Suchfeld -->
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="search" 
                           type="text" 
                           placeholder="Kunden, Firmen, E-Mail oder Adresse suchen..." 
                           class="block w-full pl-10 pr-3 py-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-gray-900 placeholder-gray-500">
                </div>
            </div>
            
            <!-- Sortierung -->
            <div>
                <select wire:model.live="sortBy" 
                        class="block w-full py-3 px-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-gray-900">
                    <option value="newest">Neueste zuerst</option>
                    <option value="oldest">Älteste zuerst</option>
                    <option value="name">Nach Name</option>
                    <option value="company">Nach Firma</option>
                </select>
            </div>
            
            <!-- View Toggle -->
            <div class="flex space-x-2">
                <button wire:click="setViewMode('cards')" 
                        class="flex-1 px-4 py-3 rounded-lg font-medium transition-all duration-300 {{ $viewMode === 'cards' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white/50 text-gray-700 hover:bg-white/70' }}">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Karten
                </button>
                <button wire:click="setViewMode('table')" 
                        class="flex-1 px-4 py-3 rounded-lg font-medium transition-all duration-300 {{ $viewMode === 'table' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white/50 text-gray-700 hover:bg-white/70' }}">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a1 1 0 011-1h12a1 1 0 011 1v16"/>
                    </svg>
                    Tabelle
                </button>
            </div>
        </div>
    </div>

    @if($viewMode === 'cards')
        <!-- Karten-Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($customers as $customer)
                <div class="bg-white/60 backdrop-blur-lg rounded-xl p-4 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    
                    <!-- Kunde Header -->
                    <div class="flex items-start justify-between mb-3 gap-2">
                        <div class="flex items-center space-x-2 min-w-0 flex-1">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0">
                                {{ substr($customer->customername ?: $customer->companyname ?: 'K', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-gray-900 truncate text-base">
                                    {{ $customer->customername ?: $customer->companyname }}
                                </h3>
                                @if($customer->customername && $customer->companyname)
                                    <p class="text-sm font-medium text-gray-700 truncate">{{ $customer->companyname }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            @if($customer->email)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Mit E-Mail</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full font-medium">Ohne E-Mail</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Kompakte Details -->
                    <div class="grid grid-cols-1 gap-1 mb-3 text-xs">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            #{{ $customer->customer_number ?: $customer->id }}
                        </div>
                        
                        @if($customer->email)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="truncate">{{ $customer->email }}</span>
                            </div>
                        @endif
                        
                        @if($customer->address || $customer->postalcode || $customer->location)
                            <div class="flex items-start text-gray-600">
                                <svg class="w-3 h-3 mr-1 mt-0.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div class="flex-1 truncate">
                                    @if($customer->address)
                                        <div class="truncate">{{ $customer->address }}</div>
                                    @endif
                                    @if($customer->postalcode || $customer->location)
                                        <div class="truncate">{{ $customer->postalcode }} {{ $customer->location }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-1 flex-wrap">
                        <a href="{{ url('/customer/' . $customer->id . '/edit') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-2 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                           title="Bearbeiten">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <a href="{{ url('/offer/create/' . $customer->id) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white text-xs py-1.5 px-2 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                           title="Angebot erstellen">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                        <a href="{{ url('/invoice/create/' . $customer->id) }}" 
                           class="bg-purple-500 hover:bg-purple-600 text-white text-xs py-1.5 px-2 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                           title="Rechnung erstellen">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                        <form action="{{ url('/customer/' . $customer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Sind Sie sicher, dass Sie diesen Kunden löschen möchten?')"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs py-1.5 px-2 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                                    title="Kunde löschen">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white/60 backdrop-blur-lg rounded-xl p-12 border border-white/20 shadow-lg text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Kunden gefunden</h3>
                    <p class="text-gray-600 mb-4">{{ $search ? 'Keine Kunden entsprechen Ihrer Suche.' : 'Sie haben noch keine Kunden angelegt.' }}</p>
                    @if(!$search)
                        <a href="{{ route('customer.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ersten Kunden anlegen
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    @else
        <!-- Tabellen-Layout -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl border border-white/20 shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="py-4 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">Kunde</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Kontakt</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Adresse</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white/30">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-white/50 transition-colors duration-200">
                                <td class="py-4 pl-6 pr-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ substr($customer->customername ?: $customer->companyname ?: 'K', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $customer->customername ?: $customer->companyname }}
                                            </div>
                                            @if($customer->customername && $customer->companyname)
                                                <div class="text-sm text-gray-600">{{ $customer->companyname }}</div>
                                            @endif
                                            <div class="text-xs text-blue-600">#{{ $customer->customer_number ?: $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4">
                                    @if($customer->email)
                                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Mit E-Mail</span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">Ohne E-Mail</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900">
                                    @if($customer->address || $customer->postalcode || $customer->location)
                                        <div>{{ $customer->address }}</div>
                                        <div class="text-gray-600">{{ $customer->postalcode }} {{ $customer->location }}</div>
                                    @else
                                        <span class="text-gray-400">Keine Adresse</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ url('/customer/' . $customer->id . '/edit') }}" 
                                           class="text-blue-600 hover:text-blue-900 font-medium">Bearbeiten</a>
                                        <a href="{{ url('/offer/create/' . $customer->id) }}" 
                                           class="text-green-600 hover:text-green-900 font-medium">+Angebot</a>
                                        <a href="{{ url('/invoice/create/' . $customer->id) }}" 
                                           class="text-purple-600 hover:text-purple-900 font-medium">+Rechnung</a>
                                        <form action="{{ url('/customer/' . $customer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Sind Sie sicher?')"
                                                    class="text-red-600 hover:text-red-900 font-medium">
                                                Löschen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Kunden gefunden</h3>
                                    <p class="text-gray-600">{{ $search ? 'Keine Kunden entsprechen Ihrer Suche.' : 'Sie haben noch keine Kunden angelegt.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    @endif
</div> 