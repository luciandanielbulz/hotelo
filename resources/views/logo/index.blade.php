<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form id="searchForm" class="form-inline" method="GET" action="{{ route('logos.index') }}">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Suchen</button>
                </form>
            </div>

            <div class="col text-right">
                    <button id="createLogoButton" class="btn btn-transparent">+ Neu</button>
                    <button id="editLogoButton"  class="btn btn-transparent" disabled>Bearbeiten</button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 20%;">Dateiname</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logos as $logo)
                    <tr data-id="{{ $logo->id }}">
                        <td>{{ $logo->name }}</td>
                        <td>{{ $logo->filename }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Keine Logos gefunden</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    <script>
        let selectedClientId = null;

        // Event-Listener für die Zeilenauswahl
        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', function() {
                document.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
                this.classList.add('selected-row');
                selectedLogoId = this.dataset.id;
                console.log(selectedLogoId);
                document.getElementById('editClientButton').disabled = false;

            });
        });

        // Event-Listener für die Bearbeiten-Schaltfläche
        document.getElementById('editLogoButton').addEventListener('click', function() {
            if (selectedLogoId) {
                window.location.href = `/logos/${selectedLogoId}/edit`; // Weiterleiten zur Bearbeitungsseite mit ID
            } else {
                alert('Bitte wählen Sie einen Kunden zum Bearbeiten aus.');
            }
        });

        document.getElementById('createLogoButton').addEventListener('click', function() {
                window.location.href = '/logos/create'; // Weiterleiten zur Bearbeitungsseite mit ID

        });


    </script>

</x-layout>
