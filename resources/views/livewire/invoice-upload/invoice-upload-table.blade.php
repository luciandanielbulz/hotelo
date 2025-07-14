<div>
    <!-- Success/Error Messages -->
    @if(session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

    <!-- Filter- und Suchbereich -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <!-- Suchfeld -->
            <div class="lg:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" placeholder="Rechnungen suchen..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm bg-white text-gray-900 placeholder-gray-500 sm:text-sm">
                </div>
            </div>
            
            <!-- Datumfilter -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="dateFrom" class="text-sm font-medium text-gray-700">Von:</label>
                    <input wire:model.live="dateFrom" type="date" id="dateFrom" 
                           class="block px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm bg-white text-gray-900 sm:text-sm">
                </div>
                
                <div class="flex items-center gap-2">
                    <label for="dateTo" class="text-sm font-medium text-gray-700">Bis:</label>
                    <input wire:model.live="dateTo" type="date" id="dateTo" 
                           class="block px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm bg-white text-gray-900 sm:text-sm">
                </div>
                
                @if($dateFrom || $dateTo)
                    <button wire:click="clearDateFilter" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Zurücksetzen
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Zeitraum-Anzeige (falls Filter aktiv) -->
        @if($dateFrom || $dateTo)
            <div class="mt-2 text-sm text-gray-600">
                <span class="font-medium">Aktiver Filter:</span>
                @if($dateFrom && $dateTo)
                    Zeitraum {{ \Carbon\Carbon::parse($dateFrom)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d.m.Y') }}
                @elseif($dateFrom)
                    Ab {{ \Carbon\Carbon::parse($dateFrom)->format('d.m.Y') }}
                @elseif($dateTo)
                    Bis {{ \Carbon\Carbon::parse($dateTo)->format('d.m.Y') }}
                @endif
            </div>
        @endif
    </div>

    <!-- Tabelle -->
    <div class="mt-8 flow-root w-full">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    @if($invoiceuploads->isEmpty())
                        <p class="text-gray-600 p-4">Keine Rechnungen gefunden.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rechnungsdatum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieferant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rechnungsnummer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preis</th>
                                    @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zahlungsart</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoiceuploads as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ $invoice->invoice_vendor ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ $invoice->description ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ $invoice->invoice_number ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            @if($invoice->amount && $invoice->currency)
                                                {{ number_format($invoice->amount, 2, ',', '.') }} {{ $invoice->currency->symbol }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium 
                                                    {{ $invoice->payment_type === 'elektronisch' ? 'bg-green-100 text-green-800' : 
                                                       ($invoice->payment_type === 'Kreditkarte' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $invoice->payment_type ?? 'elektronisch' }}
                                                </span>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('invoiceupload.edit', $invoice->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Bearbeiten
                                                </a>
                                                <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Anzeigen
                                                </a>
                                                <button wire:click="confirmDelete({{ $invoice->id }})" 
                                                        class="text-red-600 hover:text-red-900">
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
                {{ $invoiceuploads->links() }}
            </div>
        @endif
    </div>

    <!-- Lösch-Bestätigungs-Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
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
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Löschen
                        </button>
                        <button wire:click="cancelDelete" type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Abbrechen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
