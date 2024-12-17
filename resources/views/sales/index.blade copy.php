<x-layout>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Umsatzübersicht</h1>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Tabelle -->
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Jahr</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Umsatz-Netto</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($salespositions as $salesposition)
                            <tr data-id='{{ $salesposition->row }}' class="hover:bg-indigo-100 cursor-pointer">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ $salesposition->Jahr }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $salesposition->Umsatz - $salesposition->Deposit }} €</td>
                                <td class="text-right whitespace-nowrap px-3 py-4 text-sm">
                                    <button onclick="window.location.href='sales_details.php?year={{ $salesposition->row }}'" class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Details</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Datensätze gefunden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart -->
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg p-4">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            let selectedYear = null;

            // Wenn auf eine Zeile in der Tabelle geklickt wird
            $('#salesTable').on('click', 'tr', function() {
                $('#salesTable tr').removeClass('selected-row');
                $(this).addClass('selected-row');
                selectedYear = $(this).data('id');
                $('#showDetails').prop('disabled', false);
            });

            $('#showDetails').click(function() {
                if (selectedYear) {
                    window.location.href = `sales_details.php?year=${selectedYear}`;
                }
            });

            // Daten von Laravel an JavaScript übergeben
            var years = @json($salespositions->pluck('Jahr'));
            var revenues = @json($salespositions->pluck('Umsatz'));

            // Chart.js Diagramm erstellen
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{
                        label: 'Umsätze in €',
                        data: revenues,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
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
