<x-layout>
    <div class="container">

        <div class="row">
            <div class="col" style="border: 1px solid #ccc; padding: 20px; width: 80%; margin: 20px auto;">
                <div class="text-right">
                    <button id="showDetails" class="btn btn-transparent" disabled>Details</button>
                </div>
                <div class="table-responsive" id="salesTable">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Jahr</th>
                                <th scope="col">Umsatz-Netto</th>

                            </tr>
                        </thead>

                        <tbody>

                        @forelse($salespositions as $salesposition)
                            <tr data-id='{{$salesposition->row}}'>
                                <td>{{$salesposition->Jahr}}</td>
                                <td>{{$salesposition->SumExit}} €</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Keine Kunden gefunden</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col" style="border: 1px solid #ccc; padding: 20px; width: 80%; margin: 20px auto;">
                <canvas id="revenueChart"></canvas> <!-- Canvas für das Chart -->
            </div>
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
                console.log(selectedYear);
                $('#showDetails').prop('disabled', false);
            });

            $('#showDetails').click(function() {
            console.log("Details Button wurde geklickt");
            if (selectedYear) {
                $('<form>', {
                    'action': 'sales_details.php',
                    'method': 'post',
                    'style': 'display: none;',
                    'html': [
                        $('<input>', {'type': 'hidden', 'name': 'year', 'value': selectedYear}),
                    ]
                }).appendTo('body').submit();
            }
        });
        });

        var years = {{$salesposition->years}};
        var revenues = 0;

        // Chart.js Diagramm erstellen
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx, {
            type: 'line', // Du kannst auch 'line' oder 'pie' verwenden
            data: {
                labels: years, // Die Jahre auf der X-Achse
                datasets: [{
                    label: 'Umsätze in €',
                    data: revenues, // Umsätze auf der Y-Achse
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Farbe der Balken
                    borderColor: 'rgba(75, 192, 192, 1)', // Rahmenfarbe der Balken
                    borderWidth: 1 // Breite des Rahmens
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Y-Achse beginnt bei 0
                    }
                }
            }
        });
    </script>
</x-layout>
