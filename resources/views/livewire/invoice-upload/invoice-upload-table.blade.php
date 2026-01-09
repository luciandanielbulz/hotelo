<div>
    <!-- Session-Nachrichten werden als Toasts angezeigt -->
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

    <!-- Moderne Filter- und Suchbereich -->
    <div class="mb-6 bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-stone-200">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Suchfeld -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Belege durchsuchen</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" placeholder="Lieferant, Belegnummer, Beschreibung..." 
                           class="block w-full pl-10 pr-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition-all duration-200 hover:shadow-md text-sm">
                </div>
            </div>
            
            <!-- Von Datum -->
            <div>
                <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m0 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2m8 0V9a2 2 0 00-2-2H8a2 2 0 00-2 2v.01"/>
                    </svg>
                    Von Datum
                </label>
                <input wire:model.live="dateFrom" type="date" id="dateFrom" 
                       class="block w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition-all duration-200 hover:shadow-md text-sm">
            </div>
            
            <!-- Bis Datum -->
            <div>
                <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m0 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2m8 0V9a2 2 0 00-2-2H8a2 2 0 00-2 2v.01"/>
                    </svg>
                    Bis Datum
                </label>
                <input wire:model.live="dateTo" type="date" id="dateTo" 
                       class="block w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 hover:shadow-md text-sm">
            </div>
        </div>
        
        <!-- Filter-Aktionen -->
        @if($dateFrom || $dateTo || $search)
            <div class="mt-4 flex items-center justify-between bg-blue-50 rounded-lg p-3">
                <div class="flex items-center text-sm text-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                    <span class="font-medium">Aktive Filter:</span>
                    @if($search)
                        <span class="ml-2 bg-white/60 px-2 py-1 rounded text-xs">Suche: "{{ $search }}"</span>
                    @endif
                    @if($dateFrom && $dateTo)
                        <span class="ml-2 bg-white/60 px-2 py-1 rounded text-xs">{{ \Carbon\Carbon::parse($dateFrom)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d.m.Y') }}</span>
                    @elseif($dateFrom)
                        <span class="ml-2 bg-white/60 px-2 py-1 rounded text-xs">Ab {{ \Carbon\Carbon::parse($dateFrom)->format('d.m.Y') }}</span>
                    @elseif($dateTo)
                        <span class="ml-2 bg-white/60 px-2 py-1 rounded text-xs">Bis {{ \Carbon\Carbon::parse($dateTo)->format('d.m.Y') }}</span>
                    @endif
                </div>
                <button wire:click="clearDateFilter" 
                        class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-red-600 via-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-500 hover:via-red-400 hover:to-red-500 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl text-xs">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Filter zurücksetzen
                </button>
            </div>
        @endif
    </div>


    <!-- Tabelle -->
    <div class="mt-8 flow-root w-full">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden ring-1 ring-black/5 sm:rounded-lg">
                    @if($invoiceuploads->isEmpty())
                        <p class="text-gray-600 p-4">Keine Rechnungen gefunden.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-400 table-fixed">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th wire:click="sortBy('id')" class="w-16 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>ID</span>
                                            @if($sortField === 'id')
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('invoice_date')" class="w-28 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>Datum</span>
                                            @if($sortField === 'invoice_date')
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('invoice_vendor')" class="w-40 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>Lieferant</span>
                                            @if($sortField === 'invoice_vendor')
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('description')" class="w-64 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>Beschreibung</span>
                                            @if($sortField === 'description')
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('invoice_number')" class="w-40 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>Nummer</span>
                                            @if($sortField === 'invoice_number')
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('amount')" class="w-32 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                        <div class="flex items-center space-x-1">
                                            <span>Preis</span>
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
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                                        <th wire:click="sortBy('payment_type')" class="w-36 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors">
                                            <div class="flex items-center space-x-1">
                                                <span>Zahlungsart</span>
                                                @if($sortField === 'payment_type')
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
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-gray-300">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach($invoiceuploads as $invoice)
                                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" title="{{ $invoice->invoice_vendor ?? '-' }}">
                                            {{ $invoice->invoice_vendor ? \Str::limit($invoice->invoice_vendor, 15, '...') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            <div title="{{ $invoice->description ?? '-' }}">
                                                {{ $invoice->description ? \Str::limit($invoice->description, 15, '...') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" title="{{ $invoice->invoice_number ?? '-' }}">
                                            {{ $invoice->invoice_number ? \Str::limit($invoice->invoice_number, 15, '...') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            @if($invoice->amount && $invoice->currency)
                                                {{ number_format($invoice->amount, 2, ',', '.') }} {{ $invoice->currency->symbol }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold border 
                                                    {{ $invoice->payment_type === 'elektronisch' ? 'bg-green-100 text-green-800 border-green-300' : 
                                                       ($invoice->payment_type === 'Kreditkarte' ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-gray-100 text-gray-800 border-gray-300') }}">
                                                    {{ $invoice->payment_type ?? 'elektronisch' }}
                                                </span>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('invoiceupload.edit', $invoice->id) }}" 
                                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-medium rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Bearbeiten
                                                </a>
                                                <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" 
                                                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 via-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-500 hover:via-green-400 hover:to-green-500 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Anzeigen
                                                </a>
                                                <button wire:click="confirmDelete({{ $invoice->id }})" 
                                                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 via-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-500 hover:via-red-400 hover:to-red-500 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Löschen
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        
        @if(!$invoiceuploads->isEmpty())
            <div class="mt-4">
                {{ $invoiceuploads->links('livewire::tailwind') }}
            </div>
        @endif
    </div>

    <!-- Lösch-Bestätigungs-Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Rechnung löschen
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Sind Sie sicher, dass Sie diese Rechnung löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteInvoice" type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Löschen
                        </button>
                        <button wire:click="cancelDelete" type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Abbrechen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
