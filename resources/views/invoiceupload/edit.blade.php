<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-5">
            <div class="px-4 sm:px-0">
                <h2 class="text-base font-semibold text-gray-900">Rechnung bearbeiten (ID: {{ $invoice->invoice_id }})</h2>
                <p class="mt-1 text-sm text-gray-600">Hier Rechnungsdaten bearbeiten</p>
            </div>

            <!-- Formular -->
            <form method="POST" action="{{ route('invoiceupload.update', $invoice->id) }}" enctype="multipart/form-data" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-4 py-6 sm:p-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-8">
                        <div class="sm:col-span-full">
                            <label for="invoice_pdf" class="block text-sm font-medium text-gray-700 sm:col-span-2">PDF-Datei</label>
                            
                            @if($invoice->filepath)
                                <div class="mt-2 p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700 font-medium">
                                                Aktuelle Datei: {{ basename($invoice->filepath) }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-2 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Anzeigen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Wählen Sie eine neue PDF-Datei aus, um die aktuelle zu ersetzen (optional)</p>
                            @else
                                <p class="mt-1 text-sm text-gray-500">Keine Datei vorhanden</p>
                            @endif
                            
                            <div class="mt-2">
                                <input type="file" name="invoice_pdf" id="invoice_pdf" 
                                       accept=".pdf"
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                            </div>
                        </div>
                    </div>

                <!-- Rechnungsdatum -->
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="invoice_date" class="block text-sm font-medium text-gray-700">Rechnungsdatum</label>
                            <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')) }}" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required/>
                        </div>

                        <!-- Lieferant (invoice_vendor) -->
                        <div class="sm:col-span-2">
                            <label for="invoice_vendor" class="block text-sm font-medium text-gray-700">Lieferant</label>
                            <input type="text" name="invoice_vendor" id="invoice_vendor" value="{{ old('invoice_vendor', $invoice->invoice_vendor) }}" class="mt-1 p-2 block w-full border border-gray-300 rounded-md"/>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">Rechnungsnummer</label>
                            <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" class="mt-1 p-2 block w-full border border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pt-4">
                            <div class="sm:col-span-2">
                                <label for="payment_type" class="block text-sm font-medium text-gray-700">Zahlungsart</label>
                                <select name="payment_type" id="payment_type" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                    <option value="elektronisch" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'elektronisch' ? 'selected' : '' }}>elektronisch</option>
                                    <option value="nicht elektronisch" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'nicht elektronisch' ? 'selected' : '' }}>nicht elektronisch</option>
                                    <option value="Kreditkarte" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'Kreditkarte' ? 'selected' : '' }}>Kreditkarte</option>
                                </select>
                            </div>
                        </div>
                    @endif


                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-7 pt-8">
                        <div class="sm:col-span-full">
                            <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">{{ old('description', $invoice->description) }}</textarea>
                        </div>
                    </div>
                </div>



                <!-- Submit-Button -->
                <div class="flex items-center justify-end p-8">
                    <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Speichern</button>
                </div>
            </form>
    </div>
</x-layout>
