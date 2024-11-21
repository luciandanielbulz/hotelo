<x-layout>
    <div class="container">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('users.create')}}" class="btn btn-transparent">+Neu</a>
                <button id="editUserButton" class="btn btn-transparent" disabled>Bearbeiten</button>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive" id="userTable">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">E-Mail</th>
                            <th scope="col">Login</th>
                            <th scope="col">Vorname</th>
                            <th scope="col">Nachnamename</th>
                            <th scope="col">Role</th>
                            <th scope="col">Client</th>
                            <th scope="col">Aktiv</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user )
                            <tr data-id="{{ $user->user_id }}">
                                <td>{{$user->email}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->user_name}}</td>
                                <td>{{$user->lastname}}</td>
                                <td>{{$user->role_name}}</td>
                                <td>{{$user->clientname}}</td>
                                <td>{{ $user->isactive ? 'Ja' : 'Nein' }}</td>



                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        let selectedCustomerId = null;

        // Event-Listener für die Zeilenauswahl
        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', function() {
                document.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
                this.classList.add('selected-row');
                selectedUserId = this.dataset.id;
                document.getElementById('editUserButton').disabled = false;
                document.getElementById('deleteUserButton').disabled = false;
            });
        });

        // Event-Listener für die Bearbeiten-Schaltfläche
        document.getElementById('editUserButton').addEventListener('click', function() {
            if (selectedUserId) {
                window.location.href = `/users/${selectedUserId}/edit`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        // Event-Listener für die Löschen-Schaltfläche
        document.getElementById('deleteCustomerButton').addEventListener('click', function() {
            if (selectedUserId && confirm('Möchten Sie diesen Kunden wirklich löschen?')) {
                fetch(`/customer/${selectedUserId}`, {
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
                        const rowToRemove = document.querySelector(`tr[data-id="${selectedUserId}"]`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }

                        // Buttons deaktivieren
                        document.getElementById('editUserButton').disabled = true;
                        document.getElementById('deleteUserButton').disabled = true;

                        // Setze die Auswahl zurück
                        selectedCustomerId = null;
                    }
                })
                .catch(error => console.error('Fehler:', error));
            }
        });

        // Event-Listener für die Neue Kunde Schaltfläche
        document.getElementById('newUserButton').addEventListener('click', function() {
            window.location.href = '{{ route('customer.create') }}'; // Benutze route() für die Route
        });
    </script>

</x-layout>
