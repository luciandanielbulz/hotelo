<div>
    <!-- Erweiterte Such- und Filter-Sektion -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 mb-6 border border-white/20 shadow-lg">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filterung
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Suchfeld -->
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="search" 
                           type="text" 
                           placeholder="E-Mails, Kunden, Nummer oder E-Mail-Adresse suchen..." 
                           class="block w-full pl-10 pr-3 py-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-700 shadow-sm text-gray-900 placeholder-gray-500">
                </div>
            </div>
            
            <!-- Typ-Filter -->
            <div>
                <select wire:model.live="typeFilter" 
                        class="block w-full py-3 px-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-700 shadow-sm text-gray-900">
                    <option value="all">Alle Typen</option>
                    <option value="invoice">Nur Rechnungen</option>
                    <option value="offer">Nur Angebote</option>
                </select>
            </div>
            
            <!-- Sortierung -->
            <div>
                <select wire:model.live="sortBy" 
                        class="block w-full py-3 px-3 border-0 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-700 shadow-sm text-gray-900">
                    <option value="newest">Neueste zuerst</option>
                    <option value="oldest">Älteste zuerst</option>
                    <option value="customer">Nach Kunde</option>
                    <option value="type">Nach Typ</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabellenansicht -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="hidden sm:table-cell py-4 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Art</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>Nummer</span>
                            </div>
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-left text-sm font-semibold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Kunde</span>
                            </div>
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-left text-sm font-semibold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>E-Mail</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-4 text-center text-sm font-semibold text-gray-900">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Sendezeit</span>
                            </div>
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-center text-sm font-semibold text-gray-900">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span>Anhang</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-4 text-center text-sm font-semibold text-gray-900">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Status</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white/50">
                    @forelse ($outgoingEmails as $row)
                        @php
                            $type = $row->type == 1 ? 'Rechnung' : 'Angebot';
                            $typeClass = $row->type == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
                            // Farben für Nummer: Blau für Rechnungen, Grün für Angebote
                            $numberColorClass = $row->type == 1 
                                ? 'bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300' 
                                : 'bg-green-500 hover:bg-green-600';
                            $fileLink = $row->filename;
                            $customerName = $row->customername ?: $row->companyname;
                            $attachmentIcon = $row->withattachment ? 'fa-check' : 'fa-x';
                            $attachmentColor = $row->withattachment ? 'text-green-600' : 'text-red-600';
                            $statusIcon = $row->status ? 'fa-check' : 'fa-x';
                            $statusColor = $row->status ? 'text-green-600' : 'text-red-600';
                            $sendDate = new DateTime($row->sentdate);
                            $sendDate->setTimezone(new DateTimeZone('Europe/Vienna'));
                        @endphp

                        <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                            <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-6 pr-3 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeClass }}">
                                    @if($row->type == 1)
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    @endif
                                    {{ $type }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <a href="{{ route('download.file', ['filename' => $fileLink]) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 {{ $numberColorClass }} text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $row->objectnumber }}
                                </a>
                            </td>
                            <td class="hidden sm:table-cell px-3 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="truncate max-w-xs" title="{{ $customerName }}">{{ $customerName }}</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-3 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="truncate max-w-xs" title="{{ $row->getteremail }}">{{ $row->getteremail }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-900">
                                <div class="flex flex-col items-center">
                                    <span class="font-medium">{{ $sendDate->format('d.m.Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $sendDate->format('H:i:s') }}</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell whitespace-nowrap px-3 py-4 text-sm text-center">
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $row->withattachment ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i class="fa-solid {{ $attachmentIcon }} {{ $attachmentColor }} text-sm"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $row->status ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i class="fa-solid {{ $statusIcon }} {{ $statusColor }} text-sm"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Keine E-Mails gefunden</h3>
                                    <p class="text-sm text-gray-500">Es wurden keine E-Mails gefunden, die Ihren Suchkriterien entsprechen.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($outgoingEmails->hasPages())
            <div class="bg-white/70 backdrop-blur-sm px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex justify-between flex-1 sm:hidden">
                        {{ $outgoingEmails->links() }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Zeige <span class="font-medium">{{ $outgoingEmails->firstItem() ?? 0 }}</span> bis 
                                <span class="font-medium">{{ $outgoingEmails->lastItem() ?? 0 }}</span> von 
                                <span class="font-medium">{{ $outgoingEmails->total() }}</span> Ergebnissen
                            </p>
                        </div>
                        <div>
                            {{ $outgoingEmails->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>