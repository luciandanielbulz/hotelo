<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Umsatz- und Ausgabenübersicht</h1>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8">
        <!-- Tabelle: Umsatz und Ausgaben -->
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Monat</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Umsatz</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Ausgaben</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($salespositions as $salesposition)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ $salesposition->Jahr }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ str_pad($salesposition->Monat, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-900">{{ number_format($salesposition->Umsatz, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500">{{ number_format($salesposition->Ausgaben, 2, ',', '.') }} €</td>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart-Daten aus Laravel
            const chartLabels = @json($chartData['labels']);
            const chartRevenues = @json($chartData['revenue']);
            const chartExpenses = @json($chartData['expenses']);

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
                            borderWidth: 1
                        },
                        {
                            label: 'Ausgaben in €',
                            data: chartExpenses.map(e => Math.abs(e)), // Beträge positiv darstellen
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-layout>
