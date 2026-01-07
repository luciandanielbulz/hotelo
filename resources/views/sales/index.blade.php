<x-layout>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Berichtserstellung - Umsatz- und Ausgabenübersicht</h1>
                <p class="mt-2 text-sm text-gray-700">Erstellen Sie detaillierte Berichte über Ihre Umsätze, Einnahmen und Ausgaben mit vollständiger Aufschlüsselung</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-2">
                <button onclick="exportReport()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </button>
                <button onclick="showDetailedBreakdown()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
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
                    <select id="year" name="year" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="">Alle Jahre</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Berichtstyp -->
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Berichtstyp</label>
                    <select id="report_type" name="report_type" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="summary">Zusammenfassung</option>
                        <option value="detailed">Detailliert</option>
                        <option value="category_breakdown">Kategorie-Aufschlüsselung</option>
                    </select>
                </div>

                <!-- Gruppierung -->
                <div>
                    <label for="grouping" class="block text-sm font-medium text-gray-700 mb-1">Gruppierung</label>
                    <select id="grouping" name="grouping" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-900 focus:ring-blue-700">
                        <option value="year" {{ request('grouping', 'year') == 'year' ? 'selected' : '' }}>Nach Jahr</option>
                        <option value="month" {{ request('grouping') == 'month' ? 'selected' : '' }}>Nach Monat</option>
                        <option value="category" {{ request('grouping') == 'category' ? 'selected' : '' }}>Nach Kategorie</option>
                    </select>
                </div>

                <!-- Ausführen Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Bericht generieren
                    </button>
                </div>
            </form>
        </div>

        <!-- Jahresübersicht Tabelle -->
        <div class="mt-8 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        @if(request('grouping', 'year') == 'month')
                            Monatsübersicht Tabelle
                        @elseif(request('grouping') == 'category')
                            Kategorieübersicht Tabelle
                        @else
                            Jahresübersicht Tabelle
                        @endif
                    </h3>
                    <button onclick="toggleYearTable()" class="text-sm text-blue-600 hover:text-blue-800">
                        <span id="yearTableToggleText">Tabelle ausblenden</span>
                        <svg id="yearTableToggleIcon" class="inline ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="yearTableDetails">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if(request('grouping', 'year') == 'month')
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Monat</th>
                                    @elseif(request('grouping') == 'category')
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Kategorie</th>
                                    @else
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                                    @endif
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
                                        $grouping = request('grouping', 'year');
                                        if ($grouping == 'month') {
                                            $categorizedExpense = $expenses->firstWhere(function($item) use ($salesposition) {
                                                return $item->Jahr == $salesposition->Jahr && $item->Monat == $salesposition->Monat;
                                            });
                                            $categorizedIncomeItem = $categorizedIncome ? $categorizedIncome->firstWhere(function($item) use ($salesposition) {
                                                return $item->Jahr == $salesposition->Jahr && $item->Monat == $salesposition->Monat;
                                            }) : null;
                                        } elseif ($grouping == 'category') {
                                            $categorizedExpense = $expenses->firstWhere('Kategorie', $salesposition->Kategorie);
                                            $categorizedIncomeItem = $categorizedIncome ? $categorizedIncome->firstWhere('Kategorie', $salesposition->Kategorie) : null;
                                        } else {
                                            $categorizedExpense = $expenses->firstWhere('Jahr', $salesposition->Jahr);
                                            $categorizedIncomeItem = $categorizedIncome ? $categorizedIncome->firstWhere('Jahr', $salesposition->Jahr) : null;
                                        }
                                        $categorizedProfit = ($categorizedIncomeItem->Einnahmen ?? 0) - ($categorizedExpense->Ausgaben ?? 0);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        @if($grouping == 'month')
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $salesposition->Jahr }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $salesposition->Monat }}</td>
                                        @elseif($grouping == 'category')
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $salesposition->Kategorie }}</td>
                                        @else
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $salesposition->Jahr }}</td>
                                        @endif
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-900">{{ number_format($salesposition->Umsatz, 2, ',', '.') }} €</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-green-600">{{ number_format($categorizedIncomeItem->Einnahmen ?? 0, 2, ',', '.') }} €</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-red-600">{{ number_format($categorizedExpense->Ausgaben ?? 0, 2, ',', '.') }} €</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-medium {{ $categorizedProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($categorizedProfit, 2, ',', '.') }} €
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-center text-sm">
                                            <button onclick="showYearDetails('{{ $grouping == 'month' ? $salesposition->Jahr . '-' . $salesposition->Monat : ($grouping == 'category' ? $salesposition->Kategorie : $salesposition->Jahr) }}')" class="text-blue-900 hover:text-indigo-900 text-xs">
                                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Summenzeile -->
                                @php
                                    $totalRevenue = $salespositions->sum('Umsatz');
                                    $totalIncome = $categorizedIncome ? $categorizedIncome->sum('Einnahmen') : 0;
                                    $totalExpenses = $expenses->sum('Ausgaben');
                                    $totalProfit = $totalIncome - $totalExpenses;
                                @endphp
                                <tr class="bg-gray-50 border-t-2 border-gray-300">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-gray-900">
                                        @if($grouping == 'month')
                                            Gesamt
                                        @elseif($grouping == 'category')
                                            Gesamt
                                        @else
                                            Gesamt
                                        @endif
                                    </td>
                                    @if($grouping == 'month')
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">-</td>
                                    @elseif($grouping == 'category')
                                        <!-- Keine zusätzliche Spalte für Kategorie -->
                                    @endif
                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($totalRevenue, 2, ',', '.') }} €</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-green-600">{{ number_format($totalIncome, 2, ',', '.') }} €</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-red-600">{{ number_format($totalExpenses, 2, ',', '.') }} €</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($totalProfit, 2, ',', '.') }} €
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-center text-sm">
                                        <!-- Leer für Summenzeile -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detaillierte Kategorie-Aufschlüsselung -->
        <div class="mt-8 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detaillierte Kategorie-Aufschlüsselung</h3>
                    <button onclick="toggleCategoryDetails()" class="text-sm text-blue-600 hover:text-blue-800">
                        <span id="toggleText">Details ausblenden</span>
                        <svg id="toggleIcon" class="inline ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="categoryDetails">
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
                                    
                                    <!-- Summenzeile für Kategorie-Aufschlüsselung -->
                                    @php
                                        $totalIncomeAmount = $categoryBreakdown ? $categoryBreakdown->where('Typ', 'income')->sum('Betrag') : 0;
                                        $totalExpenseAmount = $categoryBreakdown ? $categoryBreakdown->where('Typ', 'expense')->sum('Betrag') : 0;
                                        $totalNetAmount = $totalIncomeAmount - $totalExpenseAmount;
                                    @endphp
                                    <tr class="bg-gray-50 border-t-2 border-gray-300">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-gray-900">Gesamt</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-center text-sm font-bold text-gray-900">-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-gray-900">-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-gray-900">-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold {{ $totalNetAmount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($totalNetAmount, 2, ',', '.') }} €
                                        </td>
                                    </tr>
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

        function toggleYearTable() {
            const yearTableDetails = document.getElementById('yearTableDetails');
            const yearTableToggleText = document.getElementById('yearTableToggleText');
            const yearTableToggleIcon = document.getElementById('yearTableToggleIcon');

            if (yearTableDetails) {
                if (yearTableDetails.classList.contains('hidden')) {
                    yearTableDetails.classList.remove('hidden');
                    yearTableToggleText.textContent = 'Tabelle ausblenden';
                    yearTableToggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    yearTableDetails.classList.add('hidden');
                    yearTableToggleText.textContent = 'Tabelle anzeigen';
                    yearTableToggleIcon.style.transform = 'rotate(0deg)';
                }
            } else {
                console.error('yearTableDetails Element nicht gefunden');
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

        function toggleYearTable() {
            const yearTableDetails = document.getElementById('yearTableDetails');
            const yearTableToggleText = document.getElementById('yearTableToggleText');
            const yearTableToggleIcon = document.getElementById('yearTableToggleIcon');
            
            if (yearTableDetails) {
                if (yearTableDetails.classList.contains('hidden')) {
                    yearTableDetails.classList.remove('hidden');
                    yearTableToggleText.textContent = 'Tabelle ausblenden';
                    yearTableToggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    yearTableDetails.classList.add('hidden');
                    yearTableToggleText.textContent = 'Tabelle anzeigen';
                    yearTableToggleIcon.style.transform = 'rotate(0deg)';
                }
            } else {
                console.error('yearTableDetails Element nicht gefunden');
            }
        }
    </script>
</x-layout>
