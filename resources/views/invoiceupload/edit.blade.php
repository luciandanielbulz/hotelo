<x-layout>
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-xl font-semibold mb-4">Rechnung bearbeiten (ID: {{ $invoice->invoice_id }})</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formular -->
        <form method="POST" action="{{ route('invoiceupload.update', $invoice->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Rechnungsdatum -->
            <div>
                <label for="invoice_date" class="block text-sm font-medium text-gray-700">Rechnungsdatum</label>
                <input
                    type="date"
                    name="invoice_date"
                    id="invoice_date"
                    value="{{ old('invoice_date', \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')) }}"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                    required
                />
            </div>

            <!-- Lieferant (invoice_vendor) -->
            <div>
                <label for="invoice_vendor" class="block text-sm font-medium text-gray-700">Lieferant</label>
                <input
                    type="text"
                    name="invoice_vendor"
                    id="invoice_vendor"
                    value="{{ old('invoice_vendor', $invoice->invoice_vendor) }}"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                />
            </div>

            <!-- Beschreibung -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                >{{ old('description', $invoice->description) }}</textarea>
            </div>

            <!-- Rechnungsnummer -->
            <div>
                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Rechnungsnummer</label>
                <input
                    type="text"
                    name="invoice_number"
                    id="invoice_number"
                    value="{{ old('invoice_number', $invoice->invoice_number) }}"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                />
            </div>

            <!-- Submit-Button -->
            <div class="flex items-center justify-end">
                <button
                    type="submit"
                    class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
