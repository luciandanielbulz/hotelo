<x-layout>
    <!-- Moderner Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Neuen Kunden anlegen</h1>
            <p class="text-gray-600">Füllen Sie die folgenden Informationen aus, um einen neuen Kunden zu erstellen</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('customer.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-white/20 text-gray-700 font-medium rounded-lg hover:bg-white/80 transition-all duration-300 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Zurück zur Übersicht
            </a>
        </div>
    </div>

    <!-- Modernisiertes Formular -->
    <div class="bg-white/60 backdrop-blur-lg rounded-2xl border border-white/20 shadow-xl">
        <form action="{{ route('customer.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Persönliche Informationen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Persönliche Informationen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-2">
                        <x-dropdown_body name="salutation_id" id="salutation_id" value="" :options="$salutations->pluck('name', 'id')" :selected="old('salutation_id', 1)" label="Anrede" placeholder="Bitte auswählen" class="w-full appearance-none rounded-lg bg-white/70 backdrop-blur-sm py-3 pl-3 pr-8 text-base text-gray-900 border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input name="title" type="text" placeholder="Titel" label="Titel" value="{{ old('title') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300"/>
                    </div>
                    <div class="sm:col-span-4">
                        <x-input name="customername" type="text" placeholder="Kundenname" label="Kundenname" value="{{ old('customername') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-4">
                        <x-input name="customer_number" type="text" placeholder="Kundennummer" label="Kundennummer" value="{{ old('customer_number') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                </div>
            </div>

            <!-- Firmeninformationen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Firmeninformationen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-6">
                        <x-input name="companyname" type="text" placeholder="Firmenname" label="Firmenname" value="{{ old('companyname') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-3">
                        <x-input name="vat_number" type="text" placeholder="UID" label="UID" value="{{ old('vat_number') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-3">
                        <x-dropdown_body name="tax_id" id="tax_id" value="" :options="$taxrates->pluck('taxrate', 'id')" :selected="old('tax_id', $standardtaxrate)" label="Umsatzsteuer in %" placeholder="Bitte auswählen" class="w-full appearance-none rounded-lg bg-white/70 backdrop-blur-sm py-3 pl-3 pr-8 text-base text-gray-900 border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                </div>
            </div>

            <!-- Adressinformationen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Adressinformationen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-6">
                        <x-input name="address" type="text" placeholder="Adresse" label="Adresse" value="{{ old('address') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-2">
                        <x-input name="postalcode" type="text" placeholder="Postleitzahl" label="Postleitzahl" value="{{ old('postalcode') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-4">
                        <x-input name="location" type="text" placeholder="Ort" label="Ort" value="{{ old('location') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-4">
                        @php
                            $countryOptions = [];
                            foreach ($countries as $country) {
                                $countryOptions[$country->id] = $country->name_de ?? $country->name;
                            }
                            // Vorauswahl: old() oder Client-Land oder null
                            $selectedCountryId = old('country_id', $clientCountryId);
                            // Stelle sicher, dass country_id als Integer behandelt wird
                            $selectedCountryId = $selectedCountryId ? (int)$selectedCountryId : null;
                        @endphp
                        <x-dropdown_body name="country_id" id="country_id" label="Land" :options="$countryOptions" :selected="$selectedCountryId" placeholder="Bitte auswählen" />
                    </div>
                </div>
            </div>

            <!-- Kontaktinformationen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Kontaktinformationen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-6">
                        <x-input name="email" type="email" placeholder="E-Mail" label="E-Mail" value="{{ old('email') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-3">
                        <x-input name="phone" type="text" placeholder="Telefonnummer" label="Telefon" value="{{ old('phone') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div class="sm:col-span-3">
                        <x-input name="fax" type="text" placeholder="Fax" label="Fax" value="{{ old('fax') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                </div>
            </div>

            <!-- Geschäftsbedingungen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Geschäftsbedingungen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-4">
                        <x-dropdown_body name="condition_id" id="condition_id" value="" :options="$conditions->pluck('conditionname', 'id')" :selected="old('condition_id', 1)" label="Konditionen" placeholder="Bitte auswählen" class="w-full appearance-none rounded-lg bg-white/70 backdrop-blur-sm py-3 pl-3 pr-8 text-base text-gray-900 border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                </div>
            </div>

            <!-- E-Mail Template Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    E-Mail Template
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input name="emailsubject" type="text" placeholder="E-Mail Betreff" label="E-Mail Betreff" value="{{ old('emailsubject', '{objekt} {objektnummer}') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300" />
                    </div>
                    <div>
                        <label for="emailbody" class="block text-sm font-medium text-gray-900 mb-2">
                            E-Mail Text
                            <span class="text-xs text-gray-500 block mt-1">
                                Variablen: aktuelles_jahr (Y0), aktuelles_monat (M0), objekt (O), objekt_mit_artikel (OA), objektnummer (ON), signatur (S), aktueller_monatsname (M0N)
                            </span>
                        </label>
                        <textarea name="emailbody" id="emailbody" rows="8" class="block w-full rounded-lg bg-white/70 backdrop-blur-sm px-4 py-3 text-base text-gray-900 border-0 shadow-sm focus:ring-2 focus:ring-blue-700 transition-all duration-300 placeholder:text-gray-400">{{ old('emailbody', 'Sehr geehrte Damen und Herren,<br><br>anbei {objekt_mit_artikel} {objektnummer}<br><br>{signatur}') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('customer.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-white/90 transition-all duration-300 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Abbrechen
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-semibold rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kunde speichern
                </button>
            </div>
        </form>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#emailbody').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Geben Sie hier Ihre E-Mail-Vorlage ein...',
                });
            });
        </script>
    @endpush
</x-layout>
