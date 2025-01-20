<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-5">
            <div class="px-4 sm:px-0">
                <h2 class="text-base font-semibold text-gray-900">Rechnung bearbeiten (ID: {{ $invoice->invoice_id }})</h2>
                <p class="mt-1 text-sm text-gray-600">Hier Rechnungsdaten bearbeiten</p>
            </div>

            <!-- Formular -->
            <form method="POST" action="{{ route('invoiceupload.update', $invoice->id) }}" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
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
