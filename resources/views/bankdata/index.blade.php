<x-layout>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Zahlungsverkehr</h1>
                <p class="mt-2 text-sm text-gray-700">Eine Übersicht aller importierten Bankdaten, Einnahmen und Ausgaben.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-2">
                <button onclick="showAddExpenseModal()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ausgabe hinzufügen
                </button>
                <button onclick="autoCategorize()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Auto-Kategorisierung
                </button>
                <button onclick="showKeywordSuggestions()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Keyword-Vorschläge
                </button>

                <a href="{{ route('bankdata.upload.form') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    JSON-Datei hochladen
                </a>
                <button 
                    type="button"
                    x-data=""
                    x-on:click="$dispatch('open-modal', 'delete-bankdata-year')"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Jahr löschen
                </button>
            </div>
        </div>
        
        @if(session('success'))
            <div class="mt-4 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L9.53 11.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter-Optionen -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Typ-Filter -->
                <div class="flex items-center space-x-2">
                    <label for="type_filter" class="text-sm font-medium text-gray-700">Typ:</label>
                    <select id="type_filter" name="type" class="rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Alle</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Einnahmen</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Ausgaben</option>
                    </select>
                </div>

                <!-- Kategorie-Filter -->
                <div class="flex items-center space-x-2">
                    <label for="category_filter" class="text-sm font-medium text-gray-700">Kategorie:</label>
                    <select id="category_filter" name="category" class="rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Alle Kategorien</option>
                        <option value="none" {{ request('category') == 'none' ? 'selected' : '' }}>Ohne Kategorie</option>
                        @foreach($filteredCategories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->type === 'income' ? 'Einnahmen' : 'Ausgaben' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jahresfilter -->
                <div class="flex items-center space-x-2">
                    <label for="year_filter" class="text-sm font-medium text-gray-700">Jahr:</label>
                    <select id="year_filter" name="year" class="rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="all">Alle Jahre</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $searchValues['year'] == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Elemente pro Seite -->
                <div class="flex items-center space-x-2">
                    <label for="per_page_filter" class="text-sm font-medium text-gray-700">Anzeigen:</label>
                    <select id="per_page_filter" name="per_page" class="rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="10" {{ $searchValues['per_page'] == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $searchValues['per_page'] == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $searchValues['per_page'] == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $searchValues['per_page'] == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $searchValues['per_page'] == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ $searchValues['per_page'] == 'all' ? 'selected' : '' }}>Alle anzeigen</option>
                    </select>
                </div>
            </div>

            <!-- Anzahl der Ergebnisse -->
            <div class="text-sm text-gray-500">
                {{ $bankData->total() }} Transaktion(en) gefunden
            </div>
        </div>

        <!-- Suchfelder -->
        <div class="mt-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Partner-Suche -->
                <div>
                    <label for="searchPartner" class="block text-sm font-medium text-gray-700 mb-1">Partner suchen</label>
                    <input type="text" 
                           id="searchPartner" 
                           value="{{ $searchValues['partner'] ?? '' }}"
                           placeholder="Partner-Name eingeben..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                </div>

                <!-- Betrag-Suche -->
                <div>
                    <label for="searchAmount" class="block text-sm font-medium text-gray-700 mb-1">Betrag suchen</label>
                    <input type="number" 
                           id="searchAmount" 
                           value="{{ $searchValues['amount'] ?? '' }}"
                           step="0.01"
                           placeholder="Betrag eingeben..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                </div>

                <!-- Datum-Suche -->
                <div>
                    <label for="searchDate" class="block text-sm font-medium text-gray-700 mb-1">Datum suchen</label>
                    <input type="date" 
                           id="searchDate" 
                           value="{{ $searchValues['date'] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                </div>

                <!-- Such-Buttons -->
                <div class="flex items-end space-x-2">
                    <button onclick="performSearch()" 
                            class="px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Suchen
                    </button>
                    <button onclick="clearSearch()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Zurücksetzen
                    </button>
                </div>
            </div>

            <!-- Aktive Filter anzeigen -->
            <div id="activeFilters" class="mt-4 hidden">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Aktive Filter:</span>
                    <div id="filterTags" class="flex flex-wrap gap-2">
                        <!-- Filter-Tags werden hier dynamisch eingefügt -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabelle -->
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Datum</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Partner</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">IBAN</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Typ</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Betrag</th>
                                                                                 <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Währung</th>
                                             <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategorie</th>
                                             <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                 <span class="sr-only">Aktionen</span>
                                             </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($bankData as $transaction)
                                    <tr class="hover:bg-gray-50 {{ $transaction->type === 'income' ? 'bg-green-50' : 'bg-red-50' }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d.m.Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ strlen($transaction->partnername ?? '') > 30 ? substr($transaction->partnername, 0, 27) . '...' : ($transaction->partnername ?? 'N/A') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $transaction->partneriban ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            @if($transaction->type === 'income')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Einnahmen
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Ausgaben
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <span class="{{ $transaction->amount < 0 ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold' }}">
                                                {{ number_format($transaction->amount, 2, ',', '.') }} {{ $transaction->currency }}
                                            </span>
                                        </td>
                                                                                         <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                     {{ $transaction->currency }}
                                                 </td>
                                                                                                   <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                      @if($transaction->category_id && $transaction->category)
                                                          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                                style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }};">
                                                              {{ $transaction->category->name }}
                                                          </span>
                                                      @else
                                                          <span class="text-gray-400">Keine Kategorie</span>
                                                      @endif
                                                  </td>
                                                 <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                     <div class="flex justify-end space-x-2">
                                                         <button type="button" 
                                                             onclick="openCategoryModal({{ $transaction->id }}, {{ $transaction->category_id ?? 'null' }}, '{{ $transaction->type }}')"
                                                             class="text-blue-900 hover:text-indigo-900 text-xs">
                                                             Kategorie
                                                         </button>
                                                         @if(!$transaction->category_id)
                                                             <button onclick="testCategorization({{ $transaction->id }})" class="text-blue-600 hover:text-blue-800 text-xs" title="Kategorisierung testen">
                                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                                 </svg>
                                                             </button>
                                                         @endif
                                                         <button type="button" 
                                                             onclick="openEditModal({{ $transaction->id }}, '{{ $transaction->partnername }}', '{{ $transaction->amount }}', '{{ $transaction->date }}', '{{ $transaction->reference }}', '{{ $transaction->type }}', {{ $transaction->category_id ?? 'null' }})"
                                                             class="text-green-600 hover:text-green-900 text-xs">
                                                             Bearbeiten
                                                         </button>
                                                     </div>
                                                 </td>
                                    </tr>
                                @empty
                                                                         <tr>
                                         <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Keine Bankdaten gefunden. 
                                            <a href="{{ route('bankdata.upload.form') }}" class="text-blue-900 hover:text-indigo-900 font-medium">
                                                Laden Sie eine JSON-Datei hoch
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

                @if($bankData->hasPages())
            <div class="mt-6">
                <x-pagination :paginator="$bankData->appends(request()->query())" />
            </div>
        @endif
             </div>
         </div>

         <!-- Kategorie Modal -->
         <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
             <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                 <div class="mt-3">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Kategorie auswählen</h3>
                     <form id="categoryForm">
                         @csrf
                         <input type="hidden" id="bankDataId" name="bankDataId">
                                                   <div class="mb-4">
                              <label for="categorySelect" class="block text-sm font-medium text-gray-700 mb-2">Kategorie:</label>
                              <select id="categorySelect" name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-700">
                                  <option value="">Keine Kategorie</option>
                                  <!-- Kategorien werden dynamisch basierend auf dem Transaktionstyp geladen -->
                              </select>
                          </div>
                         <div class="flex justify-end space-x-3">
                             <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                 Abbrechen
                             </button>
                             <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-800 rounded-md hover:bg-blue-700">
                                 Speichern
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>

         <!-- Ausgabe hinzufügen Modal -->
         <div id="addExpenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
             <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                 <div class="mt-3">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Neue Ausgabe hinzufügen</h3>
                     <form id="addExpenseForm">
                         @csrf
                         <div class="space-y-4">
                             <!-- Partner Name -->
                             <div>
                                 <label for="partnername" class="block text-sm font-medium text-gray-700 mb-1">Partner/Unternehmen *</label>
                                 <input type="text" 
                                        id="partnername" 
                                        name="partnername" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                        placeholder="z.B. Büroausstattung GmbH">
                             </div>

                             <!-- Betrag -->
                             <div>
                                 <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Betrag (€) *</label>
                                 <input type="number" 
                                        id="amount" 
                                        name="amount" 
                                        step="0.01" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                        placeholder="0.00">
                                 <p class="text-xs text-gray-500 mt-1">Negative Werte für Ausgaben, positive für Einnahmen</p>
                             </div>

                             <!-- Datum -->
                             <div>
                                 <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum *</label>
                                 <input type="date" 
                                        id="date" 
                                        name="date" 
                                        required
                                        value="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                             </div>

                             <!-- Referenz -->
                             <div>
                                 <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Referenz/Beschreibung</label>
                                 <textarea id="reference" 
                                           name="reference" 
                                           rows="3"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                           placeholder="z.B. Büromaterial, Miete, etc."></textarea>
                             </div>

                             <!-- Kategorie -->
                             <div>
                                 <label for="expense_category" class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                                 <select id="expense_category" 
                                         name="category_id"
                                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                                     <option value="">Keine Kategorie</option>
                                     @foreach($categories as $category)
                                         <option value="{{ $category->id }}" 
                                                 data-type="{{ $category->type }}"
                                                 style="color: {{ $category->color }}">
                                             {{ $category->name }} ({{ $category->type === 'income' ? 'Einnahmen' : 'Ausgaben' }})
                                         </option>
                                     @endforeach
                                 </select>
                             </div>

                             <!-- Typ -->
                             <div>
                                 <label for="expense_type" class="block text-sm font-medium text-gray-700 mb-1">Typ</label>
                                 <select id="expense_type" 
                                         name="type"
                                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                                     <option value="expense">Ausgabe</option>
                                     <option value="income">Einnahme</option>
                                 </select>
                             </div>
                         </div>

                         <div class="flex justify-end space-x-3 mt-6">
                             <button type="button" 
                                     onclick="closeAddExpenseModal()" 
                                     class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                 Abbrechen
                             </button>
                             <button type="submit" 
                                     class="px-4 py-2 text-sm font-medium text-white bg-blue-800 rounded-md hover:bg-blue-700">
                                 Ausgabe hinzufügen
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>

         <!-- Bearbeiten Modal -->
         <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
             <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                 <div class="mt-3">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Bankdaten bearbeiten</h3>
                     <form id="editForm">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="editBankDataId" name="bankDataId">
                         <div class="space-y-4">
                             <!-- Partner Name -->
                             <div>
                                 <label for="edit_partnername" class="block text-sm font-medium text-gray-700 mb-1">Partner/Unternehmen *</label>
                                 <input type="text" 
                                        id="edit_partnername" 
                                        name="partnername" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                        placeholder="z.B. Büroausstattung GmbH">
                             </div>

                             <!-- Betrag -->
                             <div>
                                 <label for="edit_amount" class="block text-sm font-medium text-gray-700 mb-1">Betrag (€) *</label>
                                 <input type="number" 
                                        id="edit_amount" 
                                        name="amount" 
                                        step="0.01" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                        placeholder="0.00">
                                 <p class="text-xs text-gray-500 mt-1">Negative Werte für Ausgaben, positive für Einnahmen</p>
                             </div>

                             <!-- Datum -->
                             <div>
                                 <label for="edit_date" class="block text-sm font-medium text-gray-700 mb-1">Datum *</label>
                                 <input type="date" 
                                        id="edit_date" 
                                        name="date" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                             </div>

                             <!-- Referenz -->
                             <div>
                                 <label for="edit_reference" class="block text-sm font-medium text-gray-700 mb-1">Referenz/Beschreibung</label>
                                 <textarea id="edit_reference" 
                                           name="reference" 
                                           rows="3"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700"
                                           placeholder="z.B. Büromaterial, Miete, etc."></textarea>
                             </div>

                             <!-- Typ -->
                             <div>
                                 <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Typ</label>
                                 <select id="edit_type" 
                                         name="type"
                                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                                     <option value="expense">Ausgabe</option>
                                     <option value="income">Einnahme</option>
                                 </select>
                             </div>

                             <!-- Kategorie -->
                             <div>
                                 <label for="edit_category" class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                                 <select id="edit_category" 
                                         name="category_id"
                                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700">
                                     <option value="">Keine Kategorie</option>
                                     @foreach($categories as $category)
                                         <option value="{{ $category->id }}" 
                                                 data-type="{{ $category->type }}"
                                                 style="color: {{ $category->color }}">
                                             {{ $category->name }} ({{ $category->type === 'income' ? 'Einnahmen' : 'Ausgaben' }})
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>

                         <div class="flex justify-end space-x-3 mt-6">
                             <button type="button" 
                                     onclick="closeEditModal()" 
                                     class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                 Abbrechen
                             </button>
                             <button type="submit" 
                                     class="px-4 py-2 text-sm font-medium text-white bg-blue-800 rounded-md hover:bg-blue-700">
                                 Speichern
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>

         <script>
                           function openCategoryModal(bankDataId, currentCategoryId, transactionType) {
                  document.getElementById('bankDataId').value = bankDataId;
                  document.getElementById('categoryModal').classList.remove('hidden');
                  
                  // Lade nur passende Kategorien für den Typ der Transaktion
                  loadCategoriesForType(transactionType, currentCategoryId);
              }

             function closeCategoryModal() {
                 document.getElementById('categoryModal').classList.add('hidden');
             }

             document.getElementById('categoryForm').addEventListener('submit', function(e) {
                 e.preventDefault();
                 
                 const bankDataId = document.getElementById('bankDataId').value;
                 const categoryId = document.getElementById('categorySelect').value;
                 
                 console.log('Sending category update:', { bankDataId, categoryId });
                 
                 fetch(`/bankdata/${bankDataId}/category`, {
                     method: 'PATCH',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     },
                     body: JSON.stringify({
                         category_id: categoryId || null
                     })
                 })
                 .then(response => {
                     console.log('Response status:', response.status);
                     if (!response.ok) {
                         throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                     }
                     return response.json();
                 })
                 .then(data => {
                     console.log('Response data:', data);
                     
                     if (data.success) {
                         // Prüfe ob neue Keywords vorgeschlagen werden
                         if (data.category && data.category.suggested_keywords && data.category.suggested_keywords.length > 0) {
                             console.log('Suggested keywords found:', data.category.suggested_keywords);
                             
                             // Erweitertes Modal für Keywords-Verwaltung
                             showAdvancedKeywordModal(data.category.name, data.category.suggested_keywords, categoryId);
                         } else {
                             console.log('No new keywords suggested, reloading page');
                             // Seite neu laden um die Änderungen anzuzeigen
                             window.location.reload();
                         }
                     } else {
                         alert('Fehler beim Aktualisieren der Kategorie: ' + (data.error || 'Unbekannter Fehler'));
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     alert('Fehler beim Aktualisieren der Kategorie: ' + error.message);
                 });
             });



             // Modal schließen wenn außerhalb geklickt wird
             document.getElementById('categoryModal').addEventListener('click', function(e) {
                 if (e.target === this) {
                     closeCategoryModal();
                 }
             });

                           // Filter-Funktionalität
              document.getElementById('type_filter').addEventListener('change', function() {
                  const type = this.value;
                  const category = document.getElementById('category_filter').value;
                  updateFilters(type, category);
              });

              document.getElementById('category_filter').addEventListener('change', function() {
                  const category = this.value;
                  const type = document.getElementById('type_filter').value;
                  updateFilters(type, category);
              });

              function updateFilters(type, category) {
                  const url = new URL(window.location);
                  
                  if (type !== 'all') {
                      url.searchParams.set('type', type);
                  } else {
                      url.searchParams.delete('type');
                  }
                  
                  if (category !== 'all') {
                      url.searchParams.set('category', category);
                  } else {
                      url.searchParams.delete('category');
                  }
                  
                  window.location.href = url.toString();
              }

              

                                                               // Lade Kategorien basierend auf dem Transaktionstyp
                function loadCategoriesForType(transactionType, currentCategoryId) {
                    const categorySelect = document.getElementById('categorySelect');
                    
                    // Lösche bestehende Optionen
                    categorySelect.innerHTML = '<option value="">Keine Kategorie</option>';
                    
                    // Debug: Zeige alle verfügbaren Kategorien
                    console.log('Transaction Type:', transactionType);
                    console.log('Current Category ID:', currentCategoryId);
                    console.log('Alle Kategorien:', @json($categories));
                    console.log('Einnahmen-Kategorien (PHP):', @json($incomeCategories));
                    console.log('Ausgaben-Kategorien (PHP):', @json($expenseCategories));
                    
                    // Wähle die passenden Kategorien basierend auf dem Typ
                    let categories = [];
                    if (transactionType === 'income') {
                        categories = @json($incomeCategories);
                        console.log('Einnahmen-Kategorien (JS):', categories);
                    } else if (transactionType === 'expense') {
                        categories = @json($expenseCategories);
                        console.log('Ausgaben-Kategorien (JS):', categories);
                    } else {
                        // Fallback: Zeige alle Kategorien wenn Typ nicht erkannt wird
                        categories = @json($categories);
                        console.log('Alle Kategorien (Fallback):', categories);
                    }
                    
                    console.log('Selected categories for type', transactionType, ':', categories);
                    
                    // Füge neue Optionen hinzu
                    if (categories && categories.length > 0) {
                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            if (category.color) {
                                option.setAttribute('data-color', category.color);
                            }
                            categorySelect.appendChild(option);
                        });
                    } else {
                        console.log('Keine Kategorien für Typ', transactionType, 'gefunden');
                    }
                    
                    // Setze die aktuelle Kategorie nach dem Laden der Optionen
                    if (currentCategoryId && currentCategoryId !== 'null' && currentCategoryId !== '') {
                        // Konvertiere currentCategoryId zu einer Zahl für den Vergleich
                        const categoryIdNum = parseInt(currentCategoryId);
                        const currentCategory = categories.find(cat => cat.id === categoryIdNum);
                        if (currentCategory) {
                            categorySelect.value = currentCategoryId;
                            console.log('Set current category to:', currentCategoryId, 'found category:', currentCategory.name);
                        } else {
                            console.log('Current category not found in filtered categories:', currentCategoryId, 'available categories:', categories.map(c => c.id));
                        }
                    } else {
                        console.log('No current category to set');
                    }
                }

        // Auto-Kategorisierung Funktionen
        function autoCategorize() {
            if (!confirm('Möchten Sie wirklich alle unkategorisierten Transaktionen automatisch kategorisieren?')) {
                return;
            }

            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Kategorisiere...';

            fetch('/bankdata/auto-categorize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Fehler: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Fehler bei der automatischen Kategorisierung');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }

        function showKeywordSuggestions() {
            fetch('/bankdata/keyword-suggestions', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let suggestionsHtml = '<div class="p-4"><h3 class="text-lg font-medium mb-4">Keyword-Vorschläge für Kategorien</h3>';
                    suggestionsHtml += '<p class="text-sm text-gray-600 mb-4">Klicken Sie auf ein Keyword, um es einer Kategorie hinzuzufügen:</p>';
                    suggestionsHtml += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">';
                    
                    Object.entries(data.suggestions).forEach(([keyword, count]) => {
                        suggestionsHtml += `
                            <div class="bg-gray-50 p-3 rounded border hover:bg-gray-100 cursor-pointer" 
                                 onclick="addKeywordToCategory('${keyword.replace(/'/g, "\\'")}')">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <strong class="text-blue-600">${keyword}</strong>
                                        <span class="text-gray-500 text-sm">(${count}x)</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                        `;
                    });
                    
                    suggestionsHtml += '</div><p class="mt-4 text-sm text-gray-600">Klicken Sie auf ein Keyword, um es einer Kategorie hinzuzufügen.</p></div>';
                    
                    // Erstelle ein Modal für die Vorschläge
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Keyword-Vorschläge</h3>
                                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                ${suggestionsHtml}
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                } else {
                    alert('Fehler: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Fehler beim Abrufen der Keyword-Vorschläge');
            });
        }

        function addKeywordToCategory(keyword) {
            // Hole alle verfügbaren Kategorien
            const categories = @json($categories);
            
            console.log('Available categories:', categories);
            
            if (!categories || categories.length === 0) {
                alert('Keine Kategorien verfügbar');
                return;
            }

            // Erstelle ein Modal für die Kategorieauswahl
            let categoryOptions = '<option value="">Kategorie auswählen...</option>';
            
            // Sortiere Kategorien alphabetisch nach Namen
            const sortedCategories = [...categories].sort((a, b) => a.name.localeCompare(b.name, 'de'));
            
            sortedCategories.forEach(category => {
                categoryOptions += `<option value="${category.id}">${category.name} (${category.type === 'income' ? 'Einnahmen' : 'Ausgaben'})</option>`;
            });

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.innerHTML = `
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Keyword zu Kategorie hinzufügen</h3>
                        <p class="text-sm text-gray-600 mb-4">Keyword: <strong>${keyword}</strong></p>
                        
                        <div class="mb-4">
                            <label for="categorySelect" class="block text-sm font-medium text-gray-700 mb-2">Kategorie auswählen:</label>
                            <select id="categorySelect" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-700">
                                ${categoryOptions}
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Abbrechen
                            </button>
                            <button onclick="confirmAddKeyword('${keyword.replace(/'/g, "\\'")}')" class="px-4 py-2 text-sm font-medium text-white bg-blue-800 rounded-md hover:bg-blue-700">
                                Hinzufügen
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Debug: Prüfe ob das Select-Element existiert
            setTimeout(() => {
                const selectElement = document.getElementById('categorySelect');
                console.log('Select element found:', selectElement);
                if (selectElement) {
                    console.log('Select options:', selectElement.options.length);
                    console.log('Current value:', selectElement.value);
                    
                    // Event-Listener für Änderungen hinzufügen
                    selectElement.addEventListener('change', function() {
                        console.log('Category selection changed to:', this.value);
                    });
                }
            }, 100);
        }



                     function showAdvancedKeywordModal(categoryName, suggestedKeywords, categoryId) {
                 // Erstelle erweitertes Modal mit Checkboxen für jedes Keyword
                 let keywordCheckboxes = '';
                 suggestedKeywords.forEach((keyword, index) => {
                     keywordCheckboxes += `
                         <div class="flex items-center space-x-3 p-2 border rounded hover:bg-gray-50">
                             <input type="checkbox" 
                                    id="keyword_${index}" 
                                    value="${keyword}" 
                                    class="keyword-checkbox h-4 w-4 text-blue-600 focus:ring-blue-700 border-gray-300 rounded">
                             <label for="keyword_${index}" class="text-sm font-medium text-gray-700">
                                 ${keyword}
                             </label>
                         </div>
                     `;
                 });

                 const modal = document.createElement('div');
                 modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                 modal.innerHTML = `
                     <div class="relative top-10 mx-auto p-6 border w-96 shadow-lg rounded-md bg-white">
                         <div class="mt-3">
                             <div class="flex justify-between items-center mb-4">
                                 <h3 class="text-lg font-medium text-gray-900">Keywords verwalten</h3>
                                 <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                     </svg>
                                 </button>
                             </div>
                             
                             <p class="text-sm text-gray-600 mb-4">
                                 Kategorie: <strong>${categoryName}</strong>
                             </p>
                             
                             <div class="mb-4">
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     Vorgeschlagene Keywords (auswählen):
                                 </label>
                                 <div class="max-h-48 overflow-y-auto border rounded p-2">
                                     ${keywordCheckboxes}
                                 </div>
                             </div>
                             
                             <div class="mb-4">
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     Zusätzliche Keywords:
                                 </label>
                                 <textarea id="additionalKeywords" 
                                           rows="3" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-700"
                                           placeholder="Weitere Keywords eingeben (kommagetrennt)"></textarea>
                             </div>
                             
                             <div class="flex justify-between items-center">
                                 <div class="flex space-x-2">
                                     <button onclick="selectAllKeywords()" class="text-sm text-blue-600 hover:text-blue-800">
                                         Alle auswählen
                                     </button>
                                     <button onclick="deselectAllKeywords()" class="text-sm text-blue-600 hover:text-blue-800">
                                         Alle abwählen
                                     </button>
                                 </div>
                                 <div class="flex space-x-3">
                                     <button onclick="this.closest('.fixed').remove(); window.location.reload();" 
                                             class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                         Abbrechen
                                     </button>
                                     <button onclick="confirmAdvancedKeywords('${categoryName}', ${categoryId})" 
                                             class="px-4 py-2 text-sm font-medium text-white bg-blue-800 rounded-md hover:bg-blue-700">
                                         Hinzufügen
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 `;
                 document.body.appendChild(modal);
             }

             function selectAllKeywords() {
                 document.querySelectorAll('.keyword-checkbox').forEach(checkbox => {
                     checkbox.checked = true;
                 });
             }

             function deselectAllKeywords() {
                 document.querySelectorAll('.keyword-checkbox').forEach(checkbox => {
                     checkbox.checked = false;
                 });
             }

             function confirmAdvancedKeywords(categoryName, categoryId) {
                 // Sammle ausgewählte Keywords
                 const selectedKeywords = [];
                 document.querySelectorAll('.keyword-checkbox:checked').forEach(checkbox => {
                     selectedKeywords.push(checkbox.value);
                 });
                 
                 // Sammle zusätzliche Keywords
                 const additionalKeywordsText = document.getElementById('additionalKeywords').value.trim();
                 if (additionalKeywordsText) {
                     const additionalKeywords = additionalKeywordsText.split(',').map(k => k.trim()).filter(k => k.length > 0);
                     selectedKeywords.push(...additionalKeywords);
                 }
                 
                 if (selectedKeywords.length === 0) {
                     alert('Bitte wählen Sie mindestens ein Keyword aus oder geben Sie zusätzliche Keywords ein.');
                     return;
                 }
                 
                 // Entferne Duplikate
                 const uniqueKeywords = [...new Set(selectedKeywords)];
                 
                 // Füge Keywords hinzu
                 addKeywordsToCategory(categoryName, uniqueKeywords, categoryId);
                 
                 // Schließe Modal
                 document.querySelectorAll('.fixed').forEach(modal => modal.remove());
             }

             function addKeywordsToCategory(categoryName, keywords, categoryId) {
            // Füge alle Keywords zur Kategorie hinzu
            Promise.all(keywords.map(keyword => 
                fetch('/bankdata/add-keyword-to-category', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        category_id: parseInt(categoryId),
                        keyword: keyword
                    })
                }).then(response => response.json())
            ))
            .then(results => {
                const successCount = results.filter(result => result.success).length;
                const totalCount = keywords.length;
                
                if (successCount === totalCount) {
                    alert(`Alle ${totalCount} Keywords wurden erfolgreich zur Kategorie "${categoryName}" hinzugefügt.`);
                } else {
                    alert(`${successCount} von ${totalCount} Keywords wurden hinzugefügt.`);
                }
                
                // Seite neu laden
                window.location.reload();
            })
            .catch(error => {
                console.error('Error adding keywords:', error);
                alert('Fehler beim Hinzufügen der Keywords. Die Seite wird neu geladen.');
                window.location.reload();
            });
        }

        function confirmAddKeyword(keyword) {
            // Warte kurz, um sicherzustellen, dass das Modal vollständig geladen ist
            setTimeout(() => {
                // Suche nach dem Select-Element im aktuellen Modal
                const modals = document.querySelectorAll('.fixed');
                let categorySelect = null;
                
                // Durchsuche alle Modals von hinten nach vorne (das neueste zuerst)
                for (let i = modals.length - 1; i >= 0; i--) {
                    const select = modals[i].querySelector('#categorySelect');
                    if (select) {
                        categorySelect = select;
                        break;
                    }
                }
                
                console.log('Found modals:', modals.length);
                console.log('Category select element:', categorySelect);
                
                if (!categorySelect) {
                    // Fallback: Suche global nach dem Element
                    categorySelect = document.getElementById('categorySelect');
                    console.log('Fallback search result:', categorySelect);
                }
                
                if (!categorySelect) {
                    alert('Fehler: Kategorieauswahl nicht gefunden');
                    return;
                }
                
                const categoryId = categorySelect.value;
                console.log('Selected category ID:', categoryId);
                
                if (!categoryId || categoryId === '') {
                    alert('Bitte wählen Sie eine Kategorie aus');
                    return;
                }

                console.log('Sending request with:', { category_id: categoryId, keyword: keyword });

                fetch('/bankdata/add-keyword-to-category', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        category_id: parseInt(categoryId),
                        keyword: keyword
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert(data.message);
                        // Schließe alle Modals
                        document.querySelectorAll('.fixed').forEach(modal => modal.remove());
                    } else {
                        alert('Fehler: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Fehler beim Hinzufügen des Keywords');
                });
            }, 200); // Warte 200ms
        }

        function testCategorization(bankDataId) {
            fetch(`/bankdata/${bankDataId}/test-categorization`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let testHtml = '<div class="p-4"><h3 class="text-lg font-medium mb-4">Kategorisierung-Test</h3>';
                    testHtml += `<p class="mb-4"><strong>Transaktion:</strong> ${data.transaction.partnername} - ${data.transaction.reference}</p>`;
                    
                    if (data.matches.length > 0) {
                        testHtml += '<div class="space-y-2">';
                        data.matches.forEach((match, index) => {
                            const scoreColor = match.score > 10 ? 'text-green-600' : match.score > 5 ? 'text-yellow-600' : 'text-red-600';
                            testHtml += `
                                <div class="border p-3 rounded ${index === 0 ? 'bg-green-50 border-green-200' : 'bg-gray-50'}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <strong>${match.category.name}</strong> (${match.category.type})
                                            <br><small class="text-gray-600">Keywords: ${match.keywords.join(', ')}</small>
                                        </div>
                                        <span class="font-medium ${scoreColor}">Score: ${match.score}</span>
                                    </div>
                                </div>
                            `;
                        });
                        testHtml += '</div>';
                    } else {
                        testHtml += '<p class="text-gray-600">Keine passenden Kategorien gefunden.</p>';
                    }
                    
                    testHtml += '</div>';
                    
                    // Erstelle ein Modal für den Test
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Kategorisierung-Test</h3>
                                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                ${testHtml}
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                } else {
                    alert('Fehler: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Fehler beim Testen der Kategorisierung');
            });
        }

        function showAddExpenseModal() {
            document.getElementById('addExpenseModal').classList.remove('hidden');
        }

        function closeAddExpenseModal() {
            document.getElementById('addExpenseModal').classList.add('hidden');
        }

        document.getElementById('addExpenseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const partnername = formData.get('partnername');
            const amount = formData.get('amount');
            const date = formData.get('date');
            const reference = formData.get('reference');
            const categoryId = formData.get('category_id');
            const type = formData.get('type');

            console.log('Sending new expense data:', { partnername, amount, date, reference, categoryId, type });

            fetch('/bankdata/add-expense', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    partnername: partnername,
                    amount: parseFloat(amount),
                    date: date,
                    reference: reference,
                    category_id: categoryId || null,
                    type: type
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Fehler beim Hinzufügen der Ausgabe: ' + (data.error || 'Unbekannter Fehler'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Fehler beim Hinzufügen der Ausgabe: ' + error.message);
            });
        });

                 // Modal schließen wenn außerhalb geklickt wird
         document.getElementById('addExpenseModal').addEventListener('click', function(e) {
             if (e.target === this) {
                 closeAddExpenseModal();
             }
         });

         // Suchfunktionen
         function performSearch() {
             const partner = document.getElementById('searchPartner').value.trim();
             const amount = document.getElementById('searchAmount').value.trim();
             const date = document.getElementById('searchDate').value;
             const perPage = document.getElementById('per_page_filter').value;
             const type = document.getElementById('type_filter').value;
             const category = document.getElementById('category_filter').value;
             const year = document.getElementById('year_filter').value;
             
             console.log('Search parameters:', { partner, amount, date, perPage, type, category, year });
             
             // Einfache URL-Konstruktion
             const baseUrl = window.location.origin + window.location.pathname;
             const params = new URLSearchParams();
             
             if (partner) {
                 params.append('partner', partner);
                 console.log('Added partner parameter:', partner);
             }
             
             if (amount) {
                 params.append('amount', amount);
                 console.log('Added amount parameter:', amount);
             }
             
             if (date) {
                 params.append('date', date);
                 console.log('Added date parameter:', date);
             }
             
             // Filter-Parameter hinzufügen
             if (type && type !== 'all') {
                 params.append('type', type);
                 console.log('Added type parameter:', type);
             }
             
             if (category && category !== 'all') {
                 params.append('category', category);
                 console.log('Added category parameter:', category);
             }
             
             if (year && year !== 'all') {
                 params.append('year', year);
                 console.log('Added year parameter:', year);
             }
             
             // Per Page Parameter hinzufügen (auch für "alle")
             params.append('per_page', perPage);
             console.log('Added per_page parameter:', perPage);
             
             const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
             console.log('Final URL:', finalUrl);
             
             // Debug: Zeige aktuelle URL
             console.log('Current URL:', window.location.href);
             console.log('Current search params:', window.location.search);
             
             // Direkte Navigation
             window.location.href = finalUrl;
         }

         function clearSearch() {
             // Alle Suchfelder zurücksetzen
             document.getElementById('searchPartner').value = '';
             document.getElementById('searchAmount').value = '';
             document.getElementById('searchDate').value = '';
             
             // Zur Seite ohne Suchparameter navigieren, aber Filter-Einstellungen beibehalten
             const currentUrl = new URL(window.location);
             currentUrl.searchParams.delete('partner');
             currentUrl.searchParams.delete('amount');
             currentUrl.searchParams.delete('date');
             
             // Filter-Einstellungen beibehalten
             const perPage = document.getElementById('per_page_filter').value;
             const type = document.getElementById('type_filter').value;
             const category = document.getElementById('category_filter').value;
             const year = document.getElementById('year_filter').value;
             
             // Per Page Parameter beibehalten (auch für "alle")
             currentUrl.searchParams.set('per_page', perPage);
             
             if (type && type !== 'all') {
                 currentUrl.searchParams.set('type', type);
             }
             
             if (category && category !== 'all') {
                 currentUrl.searchParams.set('category', category);
             }
             
             if (year && year !== 'all') {
                 currentUrl.searchParams.set('year', year);
             }
             
             window.location.href = currentUrl.toString();
         }

         // Aktive Filter anzeigen
         function updateActiveFilters() {
             const partner = document.getElementById('searchPartner').value.trim();
             const amount = document.getElementById('searchAmount').value.trim();
             const date = document.getElementById('searchDate').value;
             
             console.log('Updating active filters:', { partner, amount, date });
             
             const activeFilters = document.getElementById('activeFilters');
             const filterTags = document.getElementById('filterTags');
             
             // Filter-Tags löschen
             filterTags.innerHTML = '';
             
             let hasFilters = false;
             
             if (partner) {
                 addFilterTag('Partner: ' + partner, 'partner');
                 hasFilters = true;
             }
             
             if (amount) {
                 addFilterTag('Betrag: ' + amount + ' €', 'amount');
                 hasFilters = true;
             }
             
             if (date) {
                 addFilterTag('Datum: ' + formatDate(date), 'date');
                 hasFilters = true;
             }
             
             // Aktive Filter anzeigen/verstecken
             if (hasFilters) {
                 activeFilters.classList.remove('hidden');
                 console.log('Showing active filters');
             } else {
                 activeFilters.classList.add('hidden');
                 console.log('Hiding active filters');
             }
         }

         function addFilterTag(text, type) {
             const filterTags = document.getElementById('filterTags');
             const tag = document.createElement('span');
             tag.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
             tag.innerHTML = `
                 ${text}
                 <button onclick="removeFilter('${type}')" class="ml-1 text-blue-600 hover:text-blue-800">
                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                     </svg>
                 </button>
             `;
             filterTags.appendChild(tag);
         }

         function removeFilter(type) {
             switch(type) {
                 case 'partner':
                     document.getElementById('searchPartner').value = '';
                     break;
                 case 'amount':
                     document.getElementById('searchAmount').value = '';
                     break;
                 case 'date':
                     document.getElementById('searchDate').value = '';
                     break;
             }
             performSearch();
         }

         function formatDate(dateString) {
             const date = new Date(dateString);
             return date.toLocaleDateString('de-DE');
         }

         // Enter-Taste für Suche
         document.getElementById('searchPartner').addEventListener('keypress', function(e) {
             if (e.key === 'Enter') {
                 performSearch();
             }
         });

         document.getElementById('searchAmount').addEventListener('keypress', function(e) {
             if (e.key === 'Enter') {
                 performSearch();
             }
         });

         // Edit Modal Funktionen
         function openEditModal(id, partnername, amount, date, reference, type, categoryId) {
             document.getElementById('editBankDataId').value = id;
             document.getElementById('edit_partnername').value = partnername;
             document.getElementById('edit_amount').value = amount;
             document.getElementById('edit_date').value = date;
             document.getElementById('edit_reference').value = reference;
             document.getElementById('edit_type').value = type;
             
             // Kategorien basierend auf Typ laden
             loadEditCategoriesForType(type);
             
             // Kategorie setzen
             const categorySelect = document.getElementById('edit_category');
             if (categoryId && categoryId !== 'null') {
                 categorySelect.value = categoryId;
             } else {
                 categorySelect.value = '';
             }
             
             document.getElementById('editModal').classList.remove('hidden');
         }

         // Kategorien für Edit-Modal basierend auf Typ laden
         function loadEditCategoriesForType(transactionType) {
             const categorySelect = document.getElementById('edit_category');
             
             // Lösche bestehende Optionen außer der ersten
             categorySelect.innerHTML = '<option value="">Keine Kategorie</option>';
             
             // Alle verfügbaren Kategorien
             const allCategories = @json($categories);
             
             // Filtere Kategorien basierend auf Typ
             const filteredCategories = allCategories.filter(category => {
                 if (transactionType === 'income') {
                     return category.type === 'income';
                 } else if (transactionType === 'expense') {
                     return category.type === 'expense';
                 }
                 return true; // Fallback: Zeige alle Kategorien
             });
             
             // Füge gefilterte Kategorien hinzu
             filteredCategories.forEach(category => {
                 const option = document.createElement('option');
                 option.value = category.id;
                 option.textContent = category.name + ' (' + (category.type === 'income' ? 'Einnahmen' : 'Ausgaben') + ')';
                 option.style.color = category.color;
                 categorySelect.appendChild(option);
             });
         }

         function closeEditModal() {
             document.getElementById('editModal').classList.add('hidden');
         }

         // Edit Modal schließen wenn außerhalb geklickt wird
         document.getElementById('editModal').addEventListener('click', function(e) {
             if (e.target === this) {
                 closeEditModal();
             }
         });

         // Edit Type Change Event
         document.getElementById('edit_type').addEventListener('change', function() {
             const selectedType = this.value;
             loadEditCategoriesForType(selectedType);
             
             // Kategorie zurücksetzen wenn Typ geändert wird
             document.getElementById('edit_category').value = '';
         });

         // Edit Form Submit
         document.getElementById('editForm').addEventListener('submit', function(e) {
             e.preventDefault();
             
             const formData = new FormData(this);
             const bankDataId = document.getElementById('editBankDataId').value;
             
             fetch(`/bankdata/${bankDataId}`, {
                 method: 'PUT',
                 headers: {
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                     'Content-Type': 'application/json',
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({
                     partnername: formData.get('partnername'),
                     amount: formData.get('amount'),
                     date: formData.get('date'),
                     reference: formData.get('reference'),
                     type: formData.get('type'),
                     category_id: formData.get('category_id')
                 })
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
                     closeEditModal();
                     // Seite neu laden um Änderungen zu zeigen
                     window.location.reload();
                 } else {
                     alert('Fehler beim Speichern: ' + data.message);
                 }
             })
             .catch(error => {
                 console.error('Error:', error);
                 alert('Fehler beim Speichern der Änderungen');
             });
         });

         document.getElementById('searchDate').addEventListener('keypress', function(e) {
             if (e.key === 'Enter') {
                 performSearch();
             }
         });

         // Event-Listener für Filter-Änderungen
         document.getElementById('type_filter').addEventListener('change', function() {
             performSearch();
         });
         
         document.getElementById('category_filter').addEventListener('change', function() {
             performSearch();
         });
         
         document.getElementById('year_filter').addEventListener('change', function() {
             performSearch();
         });
         
         document.getElementById('per_page_filter').addEventListener('change', function() {
             performSearch();
         });
         
         // Aktive Filter beim Laden anzeigen
         document.addEventListener('DOMContentLoaded', function() {
             console.log('DOM loaded, updating active filters');
             updateActiveFilters();
             
             // Debug: Zeige aktuelle Suchwerte
             const partner = document.getElementById('searchPartner').value;
             const amount = document.getElementById('searchAmount').value;
             const date = document.getElementById('searchDate').value;
             console.log('Current search values:', { partner, amount, date });
             
             // Debug: Zeige URL-Parameter
             const urlParams = new URLSearchParams(window.location.search);
             console.log('URL parameters:', {
                 partner: urlParams.get('partner'),
                 amount: urlParams.get('amount'),
                 date: urlParams.get('date'),
                 year: urlParams.get('year'),
                 per_page: urlParams.get('per_page')
             });
        });
        </script>

        <!-- Modal zum Löschen von Bankdaten nach Jahr -->
        <x-delete-bankdata-year-modal :availableYears="$availableYears" />
    </x-layout>