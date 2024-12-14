<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div>
            <h2 class="text-base font-semibold text-gray-900">Kundeninformationen</h2>
            <p class="mt-1 text-sm text-gray-600">Aktualisieren Sie die Informationen des Kunden.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('customer.update', $customer->id) }}" method="POST" value = 1 class="sm:col-span-1 md:col-span-5">
            @csrf
            @method('PUT')

            <!-- Formular -->
            <div class="grid  grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-4 sm:grid-cols-1 md:col-span-1">

                <!-- Anrede -->
                <div class="sm:col-span-1">
                    <x-dropdown_body name="salutation_id" id="salutation_id" value="" :options="$salutations->pluck('name', 'id')" :selected="old('salutation', $customer->salutation)" label="Anrede" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />

                    </div>


                <!-- Titel -->
                <div class="sm:col-span-1">
                    <x-input name="title" type="text" placeholder="Titel" label="Titel" value="{{ $customer->title }}" />
                </div>

                <!-- Kundenname -->
                <div class="sm:col-span-2">
                    <x-input name="customername" type="text" placeholder="Kundenname" label="Kundenname" value="{{ $customer->customername }}" />
                    <input type="hidden" name="customerid" value="{{ $customer->id }}">
                </div>
                <!-- Firmenname -->
                <div class="sm:col-span-2">
                    <x-input name="companyname" type="text" placeholder="Firmenname" label="Firmenname" value="{{ $customer->companyname }}" />
                </div>

                <!-- Adresse -->
                <div class="sm:col-span-2">
                    <x-input name="address" type="text" placeholder="Adresse" label="Adresse" value="{{ $customer->address }}" />
                </div>

                <!-- Postleitzahl -->
                <div class="sm:col-span-1">
                    <x-input name="postalcode" type="text" placeholder="Postleitzahl" label="Postleitzahl" value="{{ $customer->postalcode }}" />
                </div>

                <!-- Ort -->
                <div class="sm:col-span-2">
                    <x-input name="location" type="text" placeholder="Ort" label="Ort" value="{{ $customer->location }}" />
                </div>

                <!-- Land -->
                <div class="sm:col-span-2">
                    <x-input name="country" type="text" placeholder="Land" label="Land" value="{{ $customer->country }}" />
                </div>

                <!-- UID -->
                <div class="sm:col-span-2">
                    <x-input name="vat_number" type="text" placeholder="UID" label="UID" value="{{ $customer->vat_number }}" />
                </div>

                <!-- Umsatzsteuer -->
                <div class="sm:col-span-2">
                    <x-dropdown_body name="tax_id" id="tax_id" value="" :options="$taxrates->pluck('taxrate', 'id')" :selected="old('taxrate', $customer->tax_id)" label="Umsatzsteuer in %" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>

                <!-- Telefonnummer -->
                <div class="sm:col-span-2">
                    <x-input name="phone" type="text" placeholder="Telefonnummer" label="Telefonnummer" value="{{ $customer->phone }}" />
                </div>

                <!-- Fax -->
                <div class="sm:col-span-2">
                    <x-input name="fax" type="text" placeholder="Fax" label="Fax" value="{{ $customer->fax }}" />
                </div>

                <!-- E-Mail -->
                <div class="sm:col-span-2">
                    <x-input name="email" type="text" placeholder="E-Mail" label="E-Mail" value="{{ $customer->email }}" />
                </div>

                <!-- Konditionen -->
                <div class="sm:col-span-2">
                    <x-dropdown_body name="condition_id" id="condition_id" value="" :options="$conditions->pluck('conditionname', 'id')" :selected="old('conditionname', $customer->condition_id)" label="Konditionen" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>


                <!-- Email Subject -->
                <div class="sm:col-span-6">
                    <x-input name="emailsubject" type="text" placeholder="E-Mail Betreff" label="E-Mail Betreff" value="{{ $customer->emailsubject }}" />
                </div>

                <!-- Email Body -->
                <div class="sm:col-span-6">
                    <label for="emailbody" class="block text-sm font-medium text-gray-900">E-Mail Text</label>
                    <div class="mt-2">
                        <textarea
                            name="emailbody"
                            id="emailbody"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                            rows="6"
                        >{{ old('email_body', $customer->emailbody) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('customer.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Änderungen speichern
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <!-- Summernote Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
    @endpush
</x-layout>
