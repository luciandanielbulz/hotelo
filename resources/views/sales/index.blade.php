<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Umsatz- und Ausgabenübersicht</h1>
            <p class="mt-2 text-sm text-gray-700">Ausgaben werden basierend auf Kategorie-Einstellungen (Prozentsatz und Verrechnungsdauer) berechnet</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <!-- Jahr-Filter -->
            <form method="GET" action="{{ route('sales.index') }}" class="flex items-center space-x-2">
                <label for="year" class="text-sm font-medium text-gray-700">Jahr:</label>
                <select id="year" name="year" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Alle Jahre</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtern
                </button>
                @if($selectedYear)
                    <a href="{{ route('sales.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Zurücksetzen
                    </a>
                @endif
            </form>
        </div>
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
                        Daten für das Jahr {{ $selectedYear }} werden angezeigt
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Übersichtskarten -->
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
                                    Einnahmen (kategorisiert) - {{ $selectedYear }}
                                @else
                                    Einnahmen (kategorisiert) - Gesamt
                                @endif
                            </dt>
                            <dd class="text-lg font-medium text-green-600">{{ number_format($categorizedIncomeItem->Einnahmen ?? 0, 2, ',', '.') }} €</dd>
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
                                    Ausgaben (kategorisiert) - {{ $selectedYear }}
                                @else
                                    Ausgaben (kategorisiert) - Gesamt
                                @endif
                            </dt>
                            <dd class="text-lg font-medium text-red-600">{{ number_format($categorizedExpense->Ausgaben ?? 0, 2, ',', '.') }} €</dd>
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
                                    Gewinn (kategorisiert) - {{ $selectedYear }}
                                @else
                                    Gewinn (kategorisiert) - Gesamt
                                @endif
                            </dt>
                            <dd class="text-lg font-medium {{ $categorizedProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($categorizedProfit, 2, ',', '.') }} €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8">
        <!-- Vereinfachte Tabelle -->
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Umsatz</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Einnahmen (kategorisiert)</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Ausgaben (kategorisiert)</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Gewinn (kategorisiert)</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart -->
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg p-4">
            <canvas id="revenueExpenseChart"></canvas>
        </div>

        <!-- Detaillierte Kategorie-Aufschlüsselung (ausklappbar) -->
        @if($categoryBreakdown->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detaillierte Ausgaben nach Kategorien</h3>
                    <button onclick="toggleCategoryDetails()" class="text-sm text-blue-600 hover:text-blue-800">
                        <span id="toggleText">Details anzeigen</span>
                        <svg id="toggleIcon" class="inline ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="categoryDetails" class="hidden">
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
                        <p><strong>Hinweis:</strong> Die Ausgaben werden basierend auf den Kategorie-Einstellungen berechnet:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong>Prozentsatz:</strong> Nur der angegebene Prozentsatz der Ausgabe wird berücksichtigt</li>
                            <li><strong>Verrechnungsdauer:</strong> Die Ausgabe wird über die angegebene Anzahl von Jahren verteilt</li>
                            <li><strong>Berechnung:</strong> Ausgaben × (Prozentsatz / 100) ÷ Verrechnungsdauer</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', function () {
            // Chart-Daten aus Laravel
            const chartLabels = @json($chartData['labels']);
            const chartRevenues = @json($chartData['revenue']);
            const chartIncomeCategorized = @json($chartData['income_categorized']);
            const chartExpensesCategorized = @json($chartData['expenses_categorized']);
            const chartProfitCategorized = @json($chartData['profit_categorized']);

            // Chart.js Diagramm erstellen
            const ctx = document.getElementById('revenueExpenseChart').getContext('2d');
            const revenueExpenseChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Umsätze in €',
                            data: chartRevenues,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Kategorisierte Einnahmen in €',
                            data: chartIncomeCategorized,
                            backgroundColor: 'rgba(34, 197, 94, 0.2)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Kategorisierte Ausgaben in €',
                            data: chartExpensesCategorized.map(e => Math.abs(e)), // Beträge positiv darstellen
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Gewinn (kategorisiert) in €',
                            data: chartProfitCategorized,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Umsatz-, Einnahmen-, Ausgaben- und Gewinnentwicklung'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('de-DE') + ' €';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout>
