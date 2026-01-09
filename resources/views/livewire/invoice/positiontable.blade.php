<div>
    <!-- Erweiterte Such- und Filter-Sektion -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 mb-6 border border-stone-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Suchfeld -->
            <div class="md:col-span-2">
                <div class="hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 hover:shadow-md hover:rounded-xl">
                    <x-search placeholder="Rechnungen, Kunden oder Nummer suchen..." />
                </div>
            </div>
            
            <!-- Sortierung -->
            <div class="w-full md:w-auto flex-shrink-0 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 hover:shadow-md hover:rounded-xl">
                <select wire:model.live="sortBy" 
                        class="block w-full py-3 px-3 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-700 text-gray-900 border border-stone-200">
                    <option value="newest">Neueste zuerst</option>
                    <option value="oldest">Älteste zuerst</option>
                    <option value="number">Nach Nummer</option>
                    <option value="customer">Nach Kunde</option>
                </select>
            </div>
            
            <!-- View Toggle - nur auf Desktop-Geräten -->
            <div class="hidden md:flex space-x-2 ml-auto">
                <button wire:click="setViewMode('cards')" 
                        class="flex-1 px-6 py-3 rounded-lg font-medium transition-all duration-300 whitespace-nowrap flex items-center justify-center hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 hover:shadow-md hover:rounded-xl {{ $viewMode === 'cards' ? 'bg-blue-900 text-white shadow-lg' : 'bg-white/50 text-gray-700 hover:bg-white/70' }} border border-stone-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Karten
                </button>
                <button wire:click="setViewMode('table')" 
                        class="flex-1 px-8 py-3 rounded-lg font-medium transition-all duration-300 whitespace-nowrap flex items-center justify-center hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 hover:shadow-md hover:rounded-xl {{ $viewMode === 'table' ? 'bg-blue-900 text-white shadow-lg' : 'bg-white/50 text-gray-700 hover:bg-white/70' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a1 1 0 011-1h12a1 1 0 011 1v16"/>
                    </svg>
                    Tabelle
                </button>
            </div>
        </div>
        <!-- Status Filter Tabs -->
        <div class="mt-4 -mx-4 sm:mx-0 px-4 sm:px-0 border border-stone-200 rounded-xl">
            <div class="flex items-center rounded-xl p-1 text-sm font-medium text-gray-600 bg-gray-50 overflow-x-auto" style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
                <div class="flex items-center space-x-1 sm:space-x-0 min-w-max">
                    <button wire:click="setStatusFilter('all')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'all' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Alle</button>
                    <button wire:click="setStatusFilter('draft')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'draft' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Entwurf</button>
                    <button wire:click="setStatusFilter('open')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'open' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Offen</button>
                    @if(auth()->user()->hasPermission('view_email_list'))
                        <button wire:click="setStatusFilter('sent')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'sent' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Gesendet</button>
                    @endif
                    <button wire:click="setStatusFilter('partial')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'partial' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Teilweise</button>
                    <button wire:click="setStatusFilter('paid')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'paid' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Bezahlt</button>
                    <button wire:click="setStatusFilter('cancelled')" class="px-2 sm:px-4 py-2 rounded-lg transition whitespace-nowrap flex-shrink-0 {{ $statusFilter === 'cancelled' ? 'bg-gray-200 text-gray-900' : 'hover:text-gray-900' }}">Storniert</button>
                </div>
            </div>
        </div>
    </div>

    @if($viewMode === 'cards')
        <!-- Karten-Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($invoices as $invoice)
                <div class="bg-white/60 backdrop-blur-lg rounded-xl p-4 border border-stone-200 hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    
                    <!-- Rechnung Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold truncate text-lg">
                                <span class="text-blue-600">#{{ $invoice->number }}</span>
                            </h3>
                            <p class="text-sm font-medium text-gray-700 truncate">
                                {{ $invoice->customername ?: $invoice->companyname ?: 'Kein Kunde' }}
                            </p>
                        </div>
                        @if($statusFilter === 'all')
                            @if($invoice->sent_date)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Gesendet</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-medium">Entwurf</span>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Kompakte Details -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}
                        </div>
                        
                        @if($invoice->total_price)
                            <div class="flex items-center text-gray-900 font-bold">
                                <svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                {{ number_format($invoice->total_price, 2, ',', '.') }} €
                            </div>
                        @else
                            <div class="flex items-center text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Kein Betrag
                            </div>
                        @endif
                        
                        @if($invoice->sent_date)
                            <div class="col-span-2 flex items-center text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Gesendet: {{ \Carbon\Carbon::parse($invoice->sent_date)->translatedFormat('d.m H:i') }}
                            </div>
                        @endif
                        
                        @if($invoice->description)
                            <div class="col-span-2 flex items-start text-gray-600">
                                <svg class="w-3 h-3 mr-1 mt-0.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="truncate">{{ $invoice->description }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3 flex-wrap gap-y-2">
                        @if((int)($invoice->status ?? 0) !== 4 || auth()->user()->hasPermission('unlock_invoices'))
                            <a href="{{ route('invoice.edit', $invoice->invoice_id) }}" 
                               class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-xl hover:scale-105 active:scale-95"
                               title="Bearbeiten">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                           title="Vorschau">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}" 
                           class="bg-red-500 hover:bg-red-600 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                           title="PDF herunterladen">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                        <a href="{{ route('invoice.copy', $invoice->invoice_id) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
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
                                        class="bg-purple-500 hover:bg-purple-600 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                                        title="E-Mail senden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        
                        <button wire:click="archiveInvoice({{ $invoice->invoice_id }})"
                                class="bg-orange-500 hover:bg-orange-600 text-white text-sm py-2.5 px-3 rounded-md transition-all duration-300 font-medium shadow-sm hover:shadow-md"
                                title="Archivieren">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6m0 0l6-6m-6 6V3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white/60 backdrop-blur-lg rounded-xl p-12 border border-stone-200 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Rechnungen gefunden</h3>
                    <p class="text-gray-600 mb-4">{{ $search ? 'Keine Rechnungen entsprechen Ihrer Suche.' : 'Sie haben noch keine Rechnungen erstellt.' }}</p>
                    @if(!$search)
                        <a href="{{ route('customer.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-semibold rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl">
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
        <div class="bg-white/60 backdrop-blur-lg rounded-xl border border-stone-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="py-4 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">
                                <button wire:click="sortByColumn('number')" class="flex items-center space-x-1 hover:text-blue-600 transition-colors">
                                    <span>Rechnung</span>
                                    @if($sortField === 'number')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                <button wire:click="sortByColumn('customer')" class="flex items-center space-x-1 hover:text-blue-600 transition-colors">
                                    <span>Kunde</span>
                                    @if($sortField === 'customer')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                <button wire:click="sortByColumn('amount')" class="flex items-center space-x-1 hover:text-blue-600 transition-colors">
                                    <span>Betrag</span>
                                    @if($sortField === 'amount')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            @if($statusFilter === 'all')
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                            @endif
                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white/30">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-white/50 transition-colors duration-200">
                                <td class="py-4 pl-6 pr-3">
                                    <div>
                                        <div class="font-medium text-gray-900"><span class="text-blue-600 font-semibold">#{{ $invoice->number }}</span></div>
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
                                @if($statusFilter === 'all')
                                    <td class="px-3 py-4">
                                        @php
                                            $status = (int) ($invoice->status ?? 0);
                                            $badge = ['label' => 'Entwurf', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'];
                                            if ($status === 1) { $badge = ['label' => 'Offen', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800']; }
                                            elseif ($status === 2) { $badge = ['label' => 'Gesendet', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800']; }
                                            elseif ($status === 3) { $badge = ['label' => 'Teilweise bezahlt', 'bg' => 'bg-orange-100', 'text' => 'text-orange-800']; }
                                            elseif ($status === 4) { $badge = ['label' => 'Bezahlt', 'bg' => 'bg-green-100', 'text' => 'text-green-800']; }
                                            elseif ($status === 6) { $badge = ['label' => 'Storniert', 'bg' => 'bg-red-100', 'text' => 'text-red-800']; }
                                        @endphp
                                        <span class="inline-block {{ $badge['bg'] }} {{ $badge['text'] }} text-xs px-2 py-1 rounded-full">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-3 py-4">
                                    <div class="flex space-x-2">
                                        @if((int)($invoice->status ?? 0) !== 4 || auth()->user()->hasPermission('unlock_invoices'))
                                            <a href="{{ route('invoice.edit', $invoice->invoice_id) }}"
                                               class="text-blue-600 hover:text-blue-900 font-medium">Bearbeiten</a>
                                        @endif
                                        <a href="{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}" 
                                           class="text-gray-600 hover:text-gray-900 font-medium">Vorschau</a>
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
                                <td colspan="{{ $statusFilter === 'all' ? 5 : 4 }}" class="px-6 py-12 text-center">
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
            {{ $invoices->links('livewire::tailwind') }}
        </div>
    @endif
</div>

<script>
    // Sofort beim Laden prüfen und View-Mode setzen
    document.addEventListener('livewire:init', function() {
        const componentId = @js($this->getId());
        
        function updateViewMode() {
            const component = Livewire.find(componentId);
            if (!component) {
                return;
            }
            
            const screenWidth = window.innerWidth;
            
            // Desktop (lg >= 1024px): IMMER Tabellenansicht
            if (screenWidth >= 1024) {
                component.set('viewMode', 'table');
                component.call('setScreenWidth', screenWidth);
            } 
            // Tablet (768px - 1024px): Kartenansicht
            else if (screenWidth >= 768 && screenWidth < 1024) {
                component.set('viewMode', 'cards');
                component.call('setScreenWidth', screenWidth);
            } 
            // Mobile (< 768px): Kartenansicht
            else {
                component.set('viewMode', 'cards');
                component.call('setScreenWidth', screenWidth);
            }
        }
        
        // Initiale Prüfung nach Livewire-Initialisierung
        setTimeout(updateViewMode, 100);
        
        // Bei Resize erneut prüfen
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateViewMode, 250);
        });
    });
</script>

