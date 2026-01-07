<x-layout>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Klientenverwaltung</h1>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none">
            <a href="{{ route('clients.create') }}" class="block rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neuen Klienten</a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="hidden sm:table-cell py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Logo</th>
                                <th scope="col" class="hidden sm:table-cell py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Firma</th>
                                <th scope="col" class="hidden sm:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Adresse</th>
                                <th scope="col" class="hidden sm:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900">PLZ-Ort</th>
                                <th scope="col" class="hidden sm:table-cell px-3 py-3.5 text-left text-sm font-semibold text-gray-900">E-Mail</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($clients as $client)
                                <tr data-id="{{ $client->id }}" class="hover:bg-indigo-100 cursor-pointer">
                                    <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">
                                        @php
                                            $logoName = $client->logo;
                                            $logoExists = false;
                                            $logoUrl = null;
                                            
                                            if ($logoName) {
                                                // Prüfe zuerst mit Storage-Facade
                                                $logoExists = \Storage::disk('public')->exists('logos/' . $logoName);
                                                
                                                // Falls Storage-Facade false zurückgibt, prüfe direkt mit file_exists
                                                if (!$logoExists) {
                                                    $directPath = storage_path('app/public/logos/' . $logoName);
                                                    $logoExists = file_exists($directPath);
                                                }
                                                
                                                if ($logoExists) {
                                                    $logoUrl = asset('storage/logos/' . rawurlencode($logoName));
                                                }
                                            }
                                        @endphp
                                        @if($logoExists && $logoUrl)
                                            <img src="{{ $logoUrl }}" 
                                                 alt="Logo" 
                                                 class="h-10 w-auto object-contain"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline'; console.error('Logo konnte nicht geladen werden: {{ $logoUrl }}');">
                                            <span class="text-gray-400" style="display: none;">Kein Logo</span>
                                        @else
                                            <span class="text-gray-400">Kein Logo{{ $logoName ? ' (' . $logoName . ')' : '' }}</span>
                                        @endif
                                    </td>
                                    <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ $client->clientname }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $client->companyname }}</td>
                                    <td class="hidden sm:table-cell whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $client->address }}</td>
                                    <td class="hidden sm:table-cell whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $client->postalcode }} - {{ $client->location }}</td>
                                    <td class="hidden sm:table-cell whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $client->email }}</td>
                                    <td class="text-right px-3 py-4 text-sm">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('clients.edit', $client->id) }}" class="inline-flex items-center justify-center rounded-md bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700 shadow-sm hover:bg-blue-200" title="Bearbeiten">
                                                <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Bearbeiten</span>
                                            </a>
                                            @if(auth()->user()->hasPermission('view_client_versions'))
                                            <a href="{{ route('clients.versions', $client->id) }}" class="hidden sm:inline-flex items-center justify-center rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                v{{ $client->version }}
                                            </a>
                                            @endif
                                            <a href="{{ route('client-settings.show', $client->id) }}" class="hidden sm:inline-flex items-center justify-center rounded-md bg-green-100 px-2 py-1 text-xs font-semibold text-green-700 shadow-sm hover:bg-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                Einstellungen
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Kunden gefunden</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <x-pagination :paginator="$clients" />
    </div>

</x-layout>
