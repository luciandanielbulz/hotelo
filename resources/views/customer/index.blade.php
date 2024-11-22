<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form id="searchForm" class="form-inline" method="GET" action="{{ route('customer.index') }}">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Suchen</button>
                </form>
            </div>

            <div class="col text-right">
                    <button id="newCustomerButton" class="btn btn-transparent">+ Neu</button>
                    <button id="editCustomerButton"  class="btn btn-transparent" disabled>Bearbeiten</button>
                    <button id="createOfferButton"  class="btn btn-transparent" disabled>+ Angebot</button>
                    <button id="createInvoiceButton"  class="btn btn-transparent" disabled>+ Rechnung</button>
                    <button id="deleteCustomerButton"  class="btn btn-transparent" disabled>Löschen</button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th style="width: 3%;">KId</th>
                    <th style="width: 30%;">Kundenname / Firmenname</th>
                    <th style="width: 35%;">Adresse</th>
                    <th style="width: 10%;">PLZ</th>
                    <th style="width: 10%;">Ort</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr data-id="{{ $customer->id }}">
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->customername}} / {{$customer->companyname}}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ $customer->postalcode }}</td>
                        <td>{{ $customer->location }}</td>
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
            {{ $customers->links() }}
        </div>


    </div>
    <script>
        let selectedCustomerId = null;

        // Event-Listener für die Zeilenauswahl
        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', function() {
                document.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
                this.classList.add('selected-row');
                selectedCustomerId = this.dataset.id;
                document.getElementById('editCustomerButton').disabled = false;
                document.getElementById('deleteCustomerButton').disabled = false;
                document.getElementById('createInvoiceButton').disabled = false;
                document.getElementById('createOfferButton').disabled = false;
            });
        });

        // Event-Listener für die Bearbeiten-Schaltfläche
        document.getElementById('editCustomerButton').addEventListener('click', function() {
            if (selectedCustomerId) {
                window.location.href = `/customer/${selectedCustomerId}/edit`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        document.getElementById('createOfferButton').addEventListener('click', function() {
            if (selectedCustomerId) {
                window.location.href = `/offer/create/${selectedCustomerId}`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        document.getElementById('createInvoiceButton').addEventListener('click', function() {
            if (selectedCustomerId) {
                window.location.href = `/invoice/create/${selectedCustomerId}`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        // Event-Listener für die Löschen-Schaltfläche
        document.getElementById('deleteCustomerButton').addEventListener('click', function() {
            if (selectedCustomerId && confirm('Möchten Sie diesen Kunden wirklich löschen?')) {
                fetch(`/customer/${selectedCustomerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Erfolgsnachricht anzeigen
                        alert(data.message);

                        // Entferne die Zeile des gelöschten Kunden aus der Tabelle
                        const rowToRemove = document.querySelector(`tr[data-id="${selectedCustomerId}"]`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }

                        // Buttons deaktivieren
                        document.getElementById('editCustomerButton').disabled = true;
                        document.getElementById('deleteCustomerButton').disabled = true;

                        // Setze die Auswahl zurück
                        selectedCustomerId = null;
                    }
                })
                .catch(error => console.error('Fehler:', error));
            }
        });

        // Event-Listener für die Neue Kunde Schaltfläche
        document.getElementById('newCustomerButton').addEventListener('click', function() {
            window.location.href = '{{ route('customer.create') }}'; // Benutze route() für die Route
        });
    </script>

</x-layout>
