<x-layout>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Berichtserstellung - Umsatz- und Ausgabenübersicht</h1>
                <p class="mt-2 text-sm text-gray-700">Erstellen Sie detaillierte Berichte über Ihre Umsätze, Einnahmen und Ausgaben mit vollständiger Aufschlüsselung</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-2">
                <button onclick="exportReport()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </button>
                <button onclick="showDetailedBreakdown()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Detaillierte Aufschlüsselung
                </button>
            </div>
        </div>

        <!-- Berichtsparameter -->
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Berichtsparameter</h3>
            <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Zeitraum -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Jahr</label>
                    <select id="year" name="year" class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Alle Jahre</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Berichtstyp -->
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Berichtstyp</label>
                    <select id="report_type" name="report_type" class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="summary">Zusammenfassung</option>
                        <option value="detailed">Detailliert</option>
                        <option value="category_breakdown">Kategorie-Aufschlüsselung</option>
                    </select>
                </div>

                <!-- Gruppierung -->
                <div>
                    <label for="grouping" class="block text-sm font-medium text-gray-700 mb-1">Gruppierung</label>
                    <select id="grouping" name="grouping" class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="year">Nach Jahr</option>
                        <option value="month">Nach Monat</option>
                        <option value="category">Nach Kategorie</option>
                    </select>
                </div>

                <!-- Ausführen Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Bericht generieren
                    </button>
                </div>
            </form>
        </div>

        @if($selectedYear)
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            Bericht für das Jahr {{ $selectedYear }} wird angezeigt
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Hauptübersicht - Wie die Summen zusammenkommen -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $displayYear = $selectedYear ?: ($salespositions->first()->Jahr ?? 'N/A');
                $categorizedIncomeItem = $categorizedIncome ? $categorizedIncome->firstWhere('Jahr', $displayYear) : null;
                $categorizedExpense = $expenses->firstWhere('Jahr', $displayYear);
                $categorizedProfit = ($categorizedIncomeItem->Einnahmen ?? 0) - ($categorizedExpense->Ausgaben ?? 0);
                $totalRevenue = $salespositions->sum('Umsatz');
            @endphp
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    @if($selectedYear)
                                        Umsatz ({{ $selectedYear }})
                                    @else
                                        Gesamtumsatz
                                    @endif
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalRevenue, 2, ',', '.') }} €</dd>
                                <dd class="text-xs text-gray-500 mt-1">Aus Rechnungen berechnet</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    @if($selectedYear)
                                        Einnahmen ({{ $selectedYear }})
                                    @else
                                        Einnahmen (Gesamt)
                                    @endif
                                </dt>
                                <dd class="text-lg font-medium text-green-600">{{ number_format($categorizedIncomeItem->Einnahmen ?? 0, 2, ',', '.') }} €</dd>
                                <dd class="text-xs text-gray-500 mt-1">Mit Kategorie-Prozentsätzen</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    @if($selectedYear)
                                        Ausgaben ({{ $selectedYear }})
                                    @else
                                        Ausgaben (Gesamt)
                                    @endif
                                </dt>
                                <dd class="text-lg font-medium text-red-600">{{ number_format($categorizedExpense->Ausgaben ?? 0, 2, ',', '.') }} €</dd>
                                <dd class="text-xs text-gray-500 mt-1">Mit Kategorie-Prozentsätzen</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 {{ $categorizedProfit >= 0 ? 'text-green-400' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    @if($selectedYear)
                                        Gewinn ({{ $selectedYear }})
                                    @else
                                        Gewinn (Gesamt)
                                    @endif
                                </dt>
                                <dd class="text-lg font-medium {{ $categorizedProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($categorizedProfit, 2, ',', '.') }} €</dd>
                                <dd class="text-xs text-gray-500 mt-1">Einnahmen - Ausgaben</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detaillierte Aufschlüsselung - Wie die Summen berechnet werden -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Einnahmen-Aufschlüsselung -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Einnahmen-Aufschlüsselung</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Kategorisierte Einnahmen:</span>
                            <span class="text-sm font-semibold text-green-600">{{ number_format($categorizedIncomeItem->Einnahmen ?? 0, 2, ',', '.') }} €</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            <p><strong>Berechnung:</strong> Summe aller Einnahmen × (Kategorie-Prozentsatz / 100) ÷ Verrechnungsdauer</p>
                            <p class="mt-1"><strong>Hinweis:</strong> Nur kategorisierte Einnahmen werden berücksichtigt</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ausgaben-Aufschlüsselung -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ausgaben-Aufschlüsselung</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Kategorisierte Ausgaben:</span>
                            <span class="text-sm font-semibold text-red-600">{{ number_format($categorizedExpense->Ausgaben ?? 0, 2, ',', '.') }} €</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            <p><strong>Berechnung:</strong> Summe aller Ausgaben × (Kategorie-Prozentsatz / 100) ÷ Verrechnungsdauer</p>
                            <p class="mt-1"><strong>Hinweis:</strong> Nur kategorisierte Ausgaben werden berücksichtigt</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jahresübersicht Tabelle -->
        <div class="mt-8 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Umsatz (Rechnungen)</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Einnahmen (kategorisiert)</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Ausgaben (kategorisiert)</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Gewinn</th>
                            <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($salespositions as $salesposition)
                            @php
                                $categorizedExpense = $expenses->firstWhere('Jahr', $salesposition->Jahr);
                                $categorizedIncomeItem = $categorizedIncome ? $categorizedIncome->firstWhere('Jahr', $salesposition->Jahr) : null;
                                $categorizedProfit = ($categorizedIncomeItem->Einnahmen ?? 0) - ($categorizedExpense->Ausgaben ?? 0);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $salesposition->Jahr }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-900">{{ number_format($salesposition->Umsatz, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-green-600">{{ number_format($categorizedIncomeItem->Einnahmen ?? 0, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-red-600">{{ number_format($categorizedExpense->Ausgaben ?? 0, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-medium {{ $categorizedProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($categorizedProfit, 2, ',', '.') }} €
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-center text-sm">
                                    <button onclick="showYearDetails({{ $salesposition->Jahr }})" class="text-indigo-600 hover:text-indigo-900 text-xs">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>



        <!-- Detaillierte Kategorie-Aufschlüsselung -->
        <div class="mt-8 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detaillierte Kategorie-Aufschlüsselung</h3>
                    <button onclick="toggleCategoryDetails()" class="text-sm text-blue-600 hover:text-blue-800">
                        <span id="toggleText">Details anzeigen</span>
                        <svg id="toggleIcon" class="inline ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="categoryDetails" class="hidden">
                    @if($categoryBreakdown && $categoryBreakdown->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategorie</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Typ</th>
                                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Prozentsatz</th>
                                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Verrechnungsdauer</th>
                                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Betrag (berechnet)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($categoryBreakdown as $breakdown)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ $breakdown->Jahr }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $breakdown->Kategorie }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-center text-sm">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $breakdown->Typ === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $breakdown->Typ === 'income' ? 'Einnahmen' : 'Ausgaben' }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-900">{{ $breakdown->Prozentsatz }}%</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-900">
                                                @if($breakdown->Verrechnungsdauer > 0)
                                                    {{ $breakdown->Verrechnungsdauer }} Jahr(e)
                                                @else
                                                    Sofort
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm {{ $breakdown->Typ === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($breakdown->Betrag, 2, ',', '.') }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Berechnungsformel:</strong></p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li><strong>Prozentsatz:</strong> Nur der angegebene Prozentsatz der Transaktion wird berücksichtigt</li>
                                <li><strong>Verrechnungsdauer:</strong> Die Transaktion wird über die angegebene Anzahl von Jahren verteilt</li>
                                <li><strong>Formel:</strong> Betrag × (Prozentsatz / 100) ÷ Verrechnungsdauer</li>
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Keine Kategorie-Daten verfügbar.</p>
                            <p class="text-sm text-gray-400 mt-2">Debug: categoryBreakdown count = {{ $categoryBreakdown ? $categoryBreakdown->count() : 'null' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportReport() {
            // PDF Export mit aktuellen Parametern
            console.log('exportReport() aufgerufen');
            
            const year = document.getElementById('year').value;
            const reportType = document.getElementById('report_type').value;
            const grouping = document.getElementById('grouping').value;
            
            console.log('Parameter:', { year, reportType, grouping });
            
            // URL mit Parametern aufbauen
            let url = '{{ route("sales.report") }}?';
            if (year) url += `year=${year}&`;
            if (reportType) url += `report_type=${reportType}&`;
            if (grouping) url += `grouping=${grouping}&`;
            
            console.log('URL:', url);
            
            // PDF Download starten
            window.open(url, '_blank');
        }



        function showDetailedBreakdown() {
            // Detaillierte Aufschlüsselung anzeigen
            console.log('showDetailedBreakdown aufgerufen');
            
            // Prüfe ob categoryDetails existiert
            const details = document.getElementById('categoryDetails');
            console.log('categoryDetails gefunden:', details);
            
            if (details) {
                // Toggle hidden class
                if (details.classList.contains('hidden')) {
                    details.classList.remove('hidden');
                    console.log('Details angezeigt');
                } else {
                    details.classList.add('hidden');
                    console.log('Details ausgeblendet');
                }
                
                // Update toggle button text
                const toggleText = document.getElementById('toggleText');
                const toggleIcon = document.getElementById('toggleIcon');
                
                if (toggleText) {
                    toggleText.textContent = details.classList.contains('hidden') ? 'Details anzeigen' : 'Details ausblenden';
                }
                
                if (toggleIcon) {
                    toggleIcon.style.transform = details.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            } else {
                console.error('categoryDetails Element nicht gefunden');
            }
        }

        function showYearDetails(year) {
            // Details für spezifisches Jahr anzeigen
            const yearData = @json($salespositions);
            const yearItem = yearData.find(item => item.Jahr == year);
            
            if (yearItem) {
                const revenue = yearItem.Umsatz || 0;
                const income = yearItem.Einnahmen_Kategorisiert || 0;
                const expenses = yearItem.Ausgaben_Kategorisiert || 0;
                const profit = yearItem.Gewinn_Kategorisiert || 0;
                
                const details = `
                    <h3>Details für Jahr ${year}</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Betrag (€)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Erklärung</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Umsatz (Rechnungen)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${revenue.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Summe aller Rechnungsbeträge</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">Einnahmen (kategorisiert)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">${income.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bankdaten mit Kategorie-Prozentsätzen</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">Ausgaben (kategorisiert)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">${expenses.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bankdaten mit Kategorie-Prozentsätzen</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Gewinn (kategorisiert)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold ${profit >= 0 ? 'text-green-600' : 'text-red-600'}">${profit.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Einnahmen - Ausgaben (kategorisiert)</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4 p-4 bg-blue-50 rounded-md">
                        <h4 class="text-sm font-medium text-blue-800">Erklärung der Unterschiede:</h4>
                        <ul class="mt-2 text-sm text-blue-700">
                            <li>• <strong>Umsätze:</strong> Summe aller Rechnungsbeträge für ${year}</li>
                            <li>• <strong>Einnahmen:</strong> Tatsächliche Geldeingänge mit Kategorie-Prozentsätzen</li>
                            <li>• <strong>Differenz:</strong> ${(revenue - income).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})} € (noch nicht eingegangene Rechnungen)</li>
                        </ul>
                    </div>
                `;
                
                // Modal oder Popup anzeigen
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                modal.innerHTML = `
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            ${details}
                            <div class="mt-6 flex justify-end">
                                <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            } else {
                alert(`Keine Daten für Jahr ${year} verfügbar.`);
            }
        }

        function toggleCategoryDetails() {
            const details = document.getElementById('categoryDetails');
            const toggleText = document.getElementById('toggleText');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                toggleText.textContent = 'Details ausblenden';
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                details.classList.add('hidden');
                toggleText.textContent = 'Details anzeigen';
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</x-layout>
