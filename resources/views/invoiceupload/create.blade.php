<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-5">
            <div class="px-4 sm:px-0">
                <h2 class="text-base font-semibold text-gray-900">Rechnung hochladen</h3>
                <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um eine neue Rechnung hochzuladen.</p>
            </div>

                <form action="{{ route('invoiceupload.upload.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
                    @csrf

                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Erfolg</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Fehler</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul role="list" class="list-disc space-y-1 pl-5">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="px-4 py-6 sm:p-6">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-8">
                            <div class="sm:col-span-full">
                                <label for="invoice_pdf" class="block text-sm font-medium text-gray-700 sm:col-span-2">PDF-Datei *</label>
                                <div class="mt-1">
                                    <input type="file" name="invoice_pdf" id="invoice_pdf" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <label for="invoice_date" class="block text-sm font-medium text-gray-900">Rechnungsdatum</label>
                                <div class="mt-2">
                                    <input type="date" name="invoice_date" id="invoice_date" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="invoice_vendor" class="block text-sm font-medium text-gray-900">Lieferant</label>
                                <div class="mt-2">
                                    <input type="text" name="invoice_vendor" id="invoice_vendor" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Rechnungsnummer</label>
                                <div class="mt-1">
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pt-4">
                            <div class="sm:col-span-2">
                                <label for="payment_type" class="block text-sm font-medium text-gray-900">Zahlungsart</label>
                                <div class="mt-2">
                                    <select name="payment_type" id="payment_type" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                        <option value="elektronisch" selected>elektronisch</option>
                                        <option value="nicht elektronisch">nicht elektronisch</option>
                                        <option value="Kreditkarte">Kreditkarte</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-7 pt-8">
                            <div class="sm:col-span-full">
                                <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                                <div class="mt-1">
                                    <textarea name="description" id="description" rows="3"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 outline outline-1 -outline-offset-1 outline-gray-300"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 flex justify-end p-8">
                        <a href="{{ route('invoiceupload.upload.create') }}"
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Abbrechen
                        </a>
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Hochladen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
