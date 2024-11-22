<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form id="searchForm" class="form-inline" method="GET" action="{{ route('clients.index') }}">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Suchen</button>
                </form>
            </div>

            <div class="col text-right">
                    <button id="createClientButton" class="btn btn-transparent">+ Neu</button>
                    <button id="editClientButton"  class="btn btn-transparent" disabled>Bearbeiten</button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 20%;">Firma</th>
                    <th style="width: 20%;">Adresse</th>
                    <th style="width: 20%;">PLZ-Ort</th>
                    <th style="width: 20%;">E-Mail</th>

                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr data-id="{{ $client->id }}">
                        <td>{{ $client->clientname }}</td>
                        <td>{{ $client->companyname }}</td>
                        <td>{{ $client->address }}</td>
                        <td>{{ $client->postalcode }} - {{$client->location}}</td>
                        <td>{{ $client->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Keine Kunden gefunden</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination-Links -->
        <div>
            {{ $clients->links() }}
        </div>


    </div>
    <script>
        let selectedClientId = null;

        // Event-Listener für die Zeilenauswahl
        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', function() {
                document.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
                this.classList.add('selected-row');
                selectedClientId = this.dataset.id;
                console.log(selectedClientId);
                document.getElementById('editClientButton').disabled = false;

            });
        });

        // Event-Listener für die Bearbeiten-Schaltfläche
        document.getElementById('editClientButton').addEventListener('click', function() {
            if (selectedClientId) {
                window.location.href = `/clients/${selectedClientId}/edit`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        document.getElementById('createClientButton').addEventListener('click', function() {
                window.location.href = '/clients/create'; // Weiterleiten zur Bearbeitungsseite mit ID

        });


    </script>

</x-layout>
