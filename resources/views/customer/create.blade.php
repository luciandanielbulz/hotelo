<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div>
            <h2 class="text-base font-semibold text-gray-900">Neuen Kunden anlegen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um einen neuen Kunden anzulegen.</p>
        </div>

        <form action="{{ route('customer.store') }}" method="POST">
        @csrf

            <!-- Formular -->
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                <div class="sm:col-span-3">
                    <label for="salutation_id" class="block text-sm font-medium text-gray-900">Anrede</label>
                    <div class="mt-2">
                        <select name="salutation_id" id="salutation_id" class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                            @foreach($salutations as $salutation)
                                <option value="{{ $salutation->id }}" {{ $salutation->id == 1 ? 'selected' : '' }}>
                                    {{ $salutation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Titel -->
                <div class="sm:col-span-3">
                    <label for="title" class="block text-sm font-medium text-gray-900">Titel</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Kundenname -->
                <div class="sm:col-span-3">
                    <label for="customername" class="block text-sm font-medium text-gray-900">Kundenname</label>
                    <div class="mt-2">
                        <input type="hidden" name="client_id" value="1">
                        <input type="hidden" name="emailsubject" value="">
                        <input type="hidden" name="emailbody" value="">
                        <input type="text" name="customername" id="customername" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Firmenname -->
                <div class="sm:col-span-3">
                    <label for="companyname" class="block text-sm font-medium text-gray-900">Firmenname</label>
                    <div class="mt-2">
                        <input type="text" name="companyname" id="companyname" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Adresse -->
                <div class="sm:col-span-3">
                    <label for="address" class="block text-sm font-medium text-gray-900">Adresse</label>
                    <div class="mt-2">
                        <input type="text" name="address" id="address" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Postleitzahl -->
                <div class="sm:col-span-2">
                    <label for="postalcode" class="block text-sm font-medium text-gray-900">Postleitzahl</label>
                    <div class="mt-2">
                        <input type="text" name="postalcode" id="postalcode" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Ort -->
                <div class="sm:col-span-1">
                    <label for="location" class="block text-sm font-medium text-gray-900">Ort</label>
                    <div class="mt-2">
                        <input type="text" name="location" id="location" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Land -->
                <div class="sm:col-span-2">
                    <label for="country" class="block text-sm font-medium text-gray-900">Land</label>
                    <div class="mt-2">
                        <input type="text" name="country" id="country" value="Österreich" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- UID -->
                <div class="sm:col-span-2">
                    <label for="vat_number" class="block text-sm font-medium text-gray-900">UID</label>
                    <div class="mt-2">
                        <input type="text" name="vat_number" id="vat_number" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Umsatzsteuer -->
                <div class="sm:col-span-2">
                    <label for="tax_id" class="block text-sm font-medium text-gray-900">Umsatzsteuer</label>
                    <div class="mt-2">
                        <select name="tax_id" id="tax_id" class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                            @foreach($taxrates as $taxrate)
                                <option value="{{ $taxrate->id }}" {{ $taxrate->id == 1 ? 'selected' : '' }}>
                                    {{ $taxrate->taxrate }} %
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Telefonnummer -->
                <div class="sm:col-span-2">
                    <label for="phone" class="block text-sm font-medium text-gray-900">Telefonnummer</label>
                    <div class="mt-2">
                        <input type="text" name="phone" id="phone" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Fax -->
                <div class="sm:col-span-2">
                    <label for="fax" class="block text-sm font-medium text-gray-900">Fax</label>
                    <div class="mt-2">
                        <input type="text" name="fax" id="fax" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- E-Mail -->
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-900">E-Mail</label>
                    <div class="mt-2">
                        <input type="text" name="email" id="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                    </div>
                </div>

                <!-- Konditionen -->
                <div class="sm:col-span-3">
                    <label for="condition_id" class="block text-sm font-medium text-gray-900">Konditionen</label>
                    <div class="mt-2">
                        <select name="condition_id" id="condition_id" class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}">
                                    {{ $condition->conditionname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <!-- Schaltflächen -->
                <a href="{{ route('customer.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Speichern
                </button>

            </div>
    </form>

</x-layout>
