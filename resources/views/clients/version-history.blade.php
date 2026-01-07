<x-layout>
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Versionshistorie</h1>
                <p class="text-gray-600">{{ $client->clientname }} - {{ $client->companyname }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $versions->count() }} Versionen insgesamt</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition ease-in-out duration-150">
                    Zurück zur Übersicht
                </a>
                <a href="{{ route('clients.edit', $client->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition ease-in-out duration-150">
                    Bearbeiten
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
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

        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            @foreach($versions as $version)
                <div class="border border-gray-200 rounded-lg p-4 {{ $version->is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $version->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    Version {{ $version->version }}
                                    @if($version->is_active)
                                        (Aktuell)
                                    @endif
                                </span>
                                <span class="text-sm text-gray-500">
                                    Gültig von: {{ $version->valid_from->format('d.m.Y H:i') }}
                                </span>
                                @if($version->valid_to)
                                    <span class="text-sm text-gray-500">
                                        bis: {{ $version->valid_to->format('d.m.Y H:i') }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Firma:</span>
                                    <span class="text-gray-900">{{ $version->companyname }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Adresse:</span>
                                    <span class="text-gray-900">{{ $version->address }}, {{ $version->postalcode }} {{ $version->location }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Email:</span>
                                    <span class="text-gray-900">{{ $version->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('clients.show-version', [$client, $version->version]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-200 transition">
                                Details ansehen
                            </a>
                            
                            @if($version->canBeDeleted())
                                <form method="POST" action="{{ route('clients.delete-version', [$client, $version->version]) }}" 
                                      class="inline-block"
                                      onsubmit="return confirm('Sind Sie sicher, dass Sie Version {{ $version->version }} löschen möchten?{{ $version->is_active ? '\n\nHinweis: Dies ist die aktive Version. Die vorherige Version wird automatisch reaktiviert.' : '' }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-md hover:bg-red-200 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Löschen
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-400 text-xs font-medium rounded-md cursor-not-allowed" 
                                      title="Letzte Version kann nicht gelöscht werden">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-5V9m0 0V7m0 2h2m-2 0H10"></path>
                                    </svg>
                                    Geschützt
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Änderungsübersicht -->
                    @php
                        $changes = $version->getChangesFromPreviousVersion();
                    @endphp
                    
                    @if($changes['type'] === 'initial')
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center text-sm text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ $changes['message'] }}
                            </div>
                        </div>
                    @elseif($changes['type'] === 'changes' && $changes['count'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $changes['count'] }} Änderung{{ $changes['count'] > 1 ? 'en' : '' }}</span>
                            </div>
                            
                            <div class="space-y-2">
                                @foreach(array_slice($changes['changes'], 0, 3) as $change)
                                    <div class="flex items-start text-xs">
                                        @if($change['type'] === 'added')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                                +
                                            </span>
                                        @elseif($change['type'] === 'removed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                                -
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mr-2">
                                                ~
                                            </span>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <span class="font-medium text-gray-700">{{ $change['label'] }}:</span>
                                            
                                            @if($change['type'] === 'added')
                                                <span class="text-green-700">→ {!! $version->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                            @elseif($change['type'] === 'removed')
                                                <span class="text-red-700 line-through">{!! $version->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                            @else
                                                <span class="text-gray-500">{!! $version->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                                <span class="text-gray-400">→</span>
                                                <span class="text-amber-700">{!! $version->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($changes['count'] > 3)
                                    <div class="text-xs">
                                        <button type="button" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors"
                                                onclick="toggleDetails({{ $version->id }})">
                                            <span id="toggle-text-{{ $version->id }}">+ {{ $changes['count'] - 3 }} weitere Änderung{{ $changes['count'] - 3 > 1 ? 'en' : '' }} anzeigen</span>
                                        </button>
                                    </div>
                                    
                                    <div id="details-{{ $version->id }}" class="hidden mt-2 space-y-2">
                                        @foreach(array_slice($changes['changes'], 3) as $change)
                                            <div class="flex items-start text-xs">
                                                @if($change['type'] === 'added')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                                        +
                                                    </span>
                                                @elseif($change['type'] === 'removed')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                                        -
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mr-2">
                                                        ~
                                                    </span>
                                                @endif
                                                
                                                <div class="flex-1 min-w-0">
                                                    <span class="font-medium text-gray-700">{{ $change['label'] }}:</span>
                                                    
                                                    @if($change['type'] === 'added')
                                                        <span class="text-green-700">→ {!! $version->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                                    @elseif($change['type'] === 'removed')
                                                        <span class="text-red-700 line-through">{!! $version->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                                    @else
                                                        <span class="text-gray-500">{!! $version->formatValueForDisplay($change['old_value'], $change['field']) !!}</span>
                                                        <span class="text-gray-400">→</span>
                                                        <span class="text-amber-700">{!! $version->formatValueForDisplay($change['new_value'], $change['field']) !!}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Keine Änderungen erkannt
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($versions->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Versionen gefunden.</p>
            </div>
        @endif
    </div>

    <script>
    function toggleDetails(versionId) {
        const detailsDiv = document.getElementById('details-' + versionId);
        const toggleText = document.getElementById('toggle-text-' + versionId);
        
        if (detailsDiv.classList.contains('hidden')) {
            detailsDiv.classList.remove('hidden');
            toggleText.textContent = '- Details ausblenden';
        } else {
            detailsDiv.classList.add('hidden');
            const hiddenCount = detailsDiv.children.length;
            toggleText.textContent = '+ ' + hiddenCount + ' weitere Änderung' + (hiddenCount > 1 ? 'en' : '') + ' anzeigen';
        }
    }
    </script>
</x-layout> 