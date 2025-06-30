<x-layout>
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Client-Details: Version {{ $specificVersion->version }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $specificVersion->companyname }} - Detaillierte Ansicht
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('clients.versions', $client) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition">
                        ← Zurück zur Versionshistorie
                    </a>
                    @if($specificVersion->is_active)
                        <a href="{{ route('clients.edit', $specificVersion) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            Bearbeiten
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Version Info -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $specificVersion->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        Version {{ $specificVersion->version }}
                        @if($specificVersion->is_active)
                            (Aktuelle Version)
                        @endif
                    </span>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Gültig von:</span>
                        {{ $specificVersion->valid_from->format('d.m.Y H:i') }}
                        @if($specificVersion->valid_to)
                            <span class="mx-2">bis</span>
                            {{ $specificVersion->valid_to->format('d.m.Y H:i') }}
                        @else
                            <span class="mx-2 text-green-600">(aktuell gültig)</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Änderungsinfo -->
            @php
                $changes = $specificVersion->getChangesFromPreviousVersion();
            @endphp
            
            @if($changes['type'] === 'initial')
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                    <div class="flex items-center text-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ $changes['message'] }}
                    </div>
                </div>
            @elseif($changes['type'] === 'changes' && $changes['count'] > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-md p-3">
                    <div class="flex items-center mb-2 text-amber-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="font-medium">{{ $changes['count'] }} Änderung{{ $changes['count'] > 1 ? 'en' : '' }} gegenüber der vorherigen Version</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($changes['changes'] as $change)
                            <div class="bg-white rounded border p-3">
                                <div class="flex items-start">
                                    @if($change['type'] === 'added')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2 mt-0.5">
                                            +
                                        </span>
                                    @elseif($change['type'] === 'removed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2 mt-0.5">
                                            -
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mr-2 mt-0.5">
                                            ~
                                        </span>
                                    @endif
                                    
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $change['label'] }}</div>
                                        
                                        @if($change['type'] === 'added')
                                            <div class="text-sm">
                                                <span class="text-gray-500">Neu:</span>
                                                <span class="text-green-700 font-medium">{!! $specificVersion->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                            </div>
                                        @elseif($change['type'] === 'removed')
                                            <div class="text-sm">
                                                <span class="text-gray-500">Entfernt:</span>
                                                <span class="text-red-700 line-through">{!! $specificVersion->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                            </div>
                                        @else
                                            <div class="text-sm space-y-1">
                                                <div>
                                                    <span class="text-gray-500">Vorher:</span>
                                                    <span class="text-gray-700">{!! $specificVersion->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Nachher:</span>
                                                    <span class="text-amber-700 font-medium">{!! $specificVersion->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Vollständige Client-Daten -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Vollständige Client-Daten</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Grunddaten -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">📋 Grunddaten</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Client-Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->clientname }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Firmenname</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->companyname }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Firmenart</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->business }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kleinunternehmer</label>
                            <p class="mt-1 text-sm {{ $specificVersion->smallbusiness ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $specificVersion->smallbusiness ? '✓ Ja' : '✗ Nein' }}
                            </p>
                        </div>
                    </div>

                    <!-- Adressdaten -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">📍 Adresse & Kontakt</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->address }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">PLZ / Ort</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->postalcode }} {{ $specificVersion->location }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-Mail</label>
                            <p class="mt-1 text-sm text-blue-600">
                                <a href="mailto:{{ $specificVersion->email }}">{{ $specificVersion->email }}</a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->phone }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Webseite</label>
                            <p class="mt-1 text-sm">
                                @if($specificVersion->webpage)
                                    <a href="http://{{ $specificVersion->webpage }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $specificVersion->webpage }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Nicht angegeben</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Steuerliche Daten -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">💼 Steuerliche Informationen</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">UID-Nummer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->vat_number ?: 'Nicht angegeben' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Steuernummer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->tax_number ?: 'Nicht angegeben' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Firmenbuchnummer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->company_registration_number ?: 'Nicht angegeben' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Geschäftsführung</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->management ?: 'Nicht angegeben' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Handelsregistergericht</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->regional_court ?: 'Nicht angegeben' }}</p>
                        </div>
                    </div>

                    <!-- Bankdaten -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">🏦 Bankdaten</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->bank }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kontonummer</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $specificVersion->accountnumber }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">BIC</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $specificVersion->bic }}</p>
                        </div>
                    </div>

                    <!-- Rechnungseinstellungen -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">📊 Rechnungseinstellungen</h4>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Letzte Angebotsnr.</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $specificVersion->lastoffer }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Multiplikator</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->offermultiplikator }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Letzte Rechnungsnr.</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $specificVersion->lastinvoice }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Multiplikator</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->invoicemultiplikator }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rechnungsnummer-Format</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->invoice_number_format ?: 'Standard' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rechnungs-Präfix</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->invoice_prefix ?: '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Angebots-Präfix</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->offer_prefix ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Design & Upload -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 border-b pb-2">🎨 Design & Uploads</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Logo</label>
                            @if($specificVersion->logo)
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                    <img src="{{ asset('storage/logos/' . $specificVersion->logo) }}" 
                                         alt="Logo" class="h-16 w-auto object-contain mx-auto">
                                    <p class="text-xs text-gray-500 text-center mt-2">{{ $specificVersion->logo }}</p>
                                </div>
                            @else
                                <p class="mt-1 text-sm text-gray-500">Kein Logo vorhanden</p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Firmenfarbe</label>
                            @if($specificVersion->color)
                                <div class="mt-2 flex items-center space-x-3 p-2 bg-gray-50 rounded">
                                    <div class="w-8 h-8 rounded-full border-2 border-gray-300 shadow-sm" style="background-color: {{ $specificVersion->color }}"></div>
                                    <span class="text-sm font-mono text-gray-900">{{ $specificVersion->color }}</span>
                                </div>
                            @else
                                <p class="mt-1 text-sm text-gray-500">Keine Farbe festgelegt</p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max. Upload-Größe</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $specificVersion->max_upload_size }} MB</p>
                        </div>
                    </div>
                </div>

                <!-- Signatur und Style (volle Breite) -->
                @if($specificVersion->signature || $specificVersion->style)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if($specificVersion->signature)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-3">✍️ Signatur</h4>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $specificVersion->signature }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($specificVersion->style)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-3">🎨 Custom Style</h4>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ $specificVersion->style }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout> 