<x-layout>

    <div class="container">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('permissions.create')}}" class="btn btn-transparent">+Neu</a>
                <button id="editPermissionButton" class="btn btn-transparent" disabled>Bearbeiten</button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class ="table-responsive" id="permissionsTable">
                    <table class="table table-hover table-sm">
                        <thead >
                            <tr>
                                <th>Name</th>
                                <th>Beschreibung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission )
                            <tr data-id="{{ $permission->id }}">
                                <td>{{$permission->name}}</td>
                                <td>{{$permission->description}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

<script>

    $(document).ready(function() {
        let selectedRoleId = null;

        // Wenn auf eine Zeile in der Tabelle geklickt wird
        $('#permissionsTable').on('click', 'tr', function() {
            $('#permissionsTable tr').removeClass('selected-row');
            $(this).addClass('selected-row');
            selectedPermissionId = $(this).data('id');
            console.log(selectedPermissionId);
            $('#editPermissionButton').prop('disabled', false);

        });

        // Wenn auf den Bearbeiten-Button geklickt wird
        $('#editPermissionButton').click(function () {
            if (selectedPermissionId) {
                // Leite den Benutzer direkt zur Edit-Seite mit der ID
                window.location.href = '{{ route('permissions.edit', ':id') }}'.replace(':id', selectedPermissionId);
            } else {
                alert('Bitte wähle zuerst eine Rolle aus.');
            }
        });

        $('#editRolePermissionButton').click(function () {
            if (selectedPermissionId) {
                // Leite den Benutzer direkt zur Edit-Seite mit der ID
                window.location.href = '{{ route('permissions.edit', ':id') }}'.replace(':id', selectedPermissionId);
            } else {
                alert('Bitte wähle zuerst eine Rolle aus.');
            }
        });


    });


</script>

</x-layout>
