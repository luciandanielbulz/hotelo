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
                           placeholder="Rechnungen, Kunden oder Nummer suchen..." 
                           class="block w-full pl-10 pr-3 py-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-gray-900 placeholder-gray-500">
                </div>
            </div>
            
            <!-- Sortierung -->
            <div>
                <select wire:model.live="sortBy" 
                        class="block w-full py-3 px-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-gray-900">
                    <option value="newest">Neueste zuerst</option>
                    <option value="oldest">Älteste zuerst</option>
                    <option value="number">Nach Nummer</option>
                    <option value="customer">Nach Kunde</option>
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
            @forelse($invoices as $invoice)
                <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    
                    <!-- Rechnung Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">
                                Rechnung #{{ $invoice->number }}
                            </h3>
                            <p class="text-sm text-gray-600 truncate">
                                {{ $invoice->customername ?: $invoice->companyname ?: 'Kein Kunde' }}
                            </p>
                            <p class="text-xs text-blue-600 font-medium">
                                {{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}
                            </p>
                        </div>
                        @if($invoice->sent_date)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Gesendet</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Entwurf</span>
                        @endif
                    </div>
                    
                    <!-- Rechnung Details -->
                    <div class="space-y-3 mb-6">
                        @if($invoice->description)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="truncate">{{ $invoice->description }}</span>
                            </div>
                        @endif
                        
                        @if($invoice->total_price)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span class="font-medium">{{ number_format($invoice->total_price, 2, ',', '.') }} €</span>
                            </div>
                        @endif

                        @if($invoice->sent_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($invoice->sent_date)->translatedFormat('d.m.Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-2 mb-2">
                        <a href="{{ route('invoice.edit', $invoice->invoice_id) }}" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded-lg transition-all duration-300 text-center font-medium shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Bearbeiten
                        </a>
                        <a href="{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}" 
                           class="bg-red-500 hover:bg-red-600 text-white text-sm py-2 px-3 rounded-lg transition-all duration-300 font-medium shadow-md hover:shadow-lg"
                           title="PDF herunterladen">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('invoice.copy', $invoice->invoice_id) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-3 rounded-lg transition-all duration-300 font-medium shadow-md hover:shadow-lg"
                           title="Rechnung kopieren">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </a>
                        
                        @if(auth()->user()->hasPermission('send_emails'))
                            <form action="{{ route('invoice.sendmail') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="objectid" value="{{ $invoice->invoice_id }}">
                                <input type="hidden" name="objecttype" value="invoice">
                                <button type="submit"
                                        class="bg-purple-500 hover:bg-purple-600 text-white text-sm py-2 px-3 rounded-lg transition-all duration-300 font-medium shadow-md hover:shadow-lg"
                                        title="E-Mail senden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        
                        <button wire:click="archiveInvoice({{ $invoice->invoice_id }})"
                                class="bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 px-3 rounded-lg transition-all duration-300 font-medium shadow-md hover:shadow-lg"
                                title="Archivieren">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6m0 0l6-6m-6 6V3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white/60 backdrop-blur-lg rounded-xl p-12 border border-white/20 shadow-lg text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Rechnungen gefunden</h3>
                    <p class="text-gray-600 mb-4">{{ $search ? 'Keine Rechnungen entsprechen Ihrer Suche.' : 'Sie haben noch keine Rechnungen erstellt.' }}</p>
                    @if(!$search)
                        <a href="{{ route('customer.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Erste Rechnung erstellen
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
                            <th scope="col" class="py-4 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">Rechnung</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Kunde</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Betrag</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white/30">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-white/50 transition-colors duration-200">
                                <td class="py-4 pl-6 pr-3">
                                    <div>
                                        <div class="font-medium text-gray-900">Rechnung #{{ $invoice->number }}</div>
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}</div>
                                        @if($invoice->description)
                                            <div class="text-xs text-gray-500">{{ $invoice->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm text-gray-900">{{ $invoice->customername ?: $invoice->companyname ?: 'Kein Kunde' }}</div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900">
                                    @if($invoice->total_price)
                                        <span class="font-medium">{{ number_format($invoice->total_price, 2, ',', '.') }} €</span>
                                    @else
                                        <span class="text-gray-400">Kein Betrag</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    @if($invoice->sent_date)
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            Gesendet {{ \Carbon\Carbon::parse($invoice->sent_date)->translatedFormat('d.m.Y') }}
                                        </span>
                                    @else
                                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Entwurf</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('invoice.edit', $invoice->invoice_id) }}"
                                           class="text-blue-600 hover:text-blue-900 font-medium">Bearbeiten</a>
                                        <a href="{{ route('invoice.copy', $invoice->invoice_id) }}"
                                           class="text-green-600 hover:text-green-900 font-medium">Kopieren</a>
                                        <a href="{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}" 
                                           class="text-red-600 hover:text-red-900 font-medium">PDF</a>
                                        @if(auth()->user()->hasPermission('send_emails'))
                                            <form action="{{ route('invoice.sendmail') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="objectid" value="{{ $invoice->invoice_id }}">
                                                <input type="hidden" name="objecttype" value="invoice">
                                                <button type="submit" class="text-purple-600 hover:text-purple-900 font-medium">Senden</button>
                                            </form>
                                        @endif
                                        <button wire:click="archiveInvoice({{ $invoice->invoice_id }})"
                                                class="text-orange-600 hover:text-orange-900 font-medium">
                                            Archivieren
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Rechnungen gefunden</h3>
                                    <p class="text-gray-600">{{ $search ? 'Keine Rechnungen entsprechen Ihrer Suche.' : 'Sie haben noch keine Rechnungen erstellt.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    <!-- Pagination -->
    @if($invoices->hasPages())
        <div class="mt-6">
    {{ $invoices->links() }}
</div>
    @endif
</div>

