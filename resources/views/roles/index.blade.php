<x-layout>

        <div class="container">
            <div class="row">
                <div class="col text-right">
                    <a href="{{ route('roles.create')}}" class="btn btn-transparent">+Neu</a>
                    <button id="editRoleButton" class="btn btn-transparent" disabled>Bearbeiten</button>
                    <button id="editRolePermissionButton" class="btn btn-transparent" disabled>Rechte bearbeiten</button>


                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class ="table-responsive" id="rolesTable">
                        <table class="table table-hover table-sm">
                            <thead >
                                <tr>
                                    <th>Name</th>
                                    <th>Beschreibung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role )
                                <tr data-id="{{ $role->id }}">
                                    <td>{{$role->name}}</td>
                                    <td>{{$role->description}}</td>
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
            $('#rolesTable').on('click', 'tr', function() {
                $('#rolesTable tr').removeClass('selected-row');
                $(this).addClass('selected-row');
                selectedRoleId = $(this).data('id');
                console.log(selectedRoleId);
                $('#editRoleButton').prop('disabled', false);
                $('#editRolePermissionButton').prop('disabled', false);
            });

            // Wenn auf den Bearbeiten-Button geklickt wird
            $('#editRoleButton').click(function () {
                if (selectedRoleId) {
                    // Leite den Benutzer direkt zur Edit-Seite mit der ID
                    window.location.href = '{{ route('roles.edit', ':id') }}'.replace(':id', selectedRoleId);
                } else {
                    alert('Bitte wähle zuerst eine Rolle aus.');
                }
            });

            $('#editRolePermissionButton').click(function () {
                if (selectedRoleId) {
                    // Leite den Benutzer direkt zur Edit-Seite mit der ID
                    window.location.href = '{{ route('rolepermissions.edit', ':id') }}'.replace(':id', selectedRoleId);
                } else {
                    alert('Bitte wähle zuerst eine Rolle aus.');
                }
            });


        });


    </script>

</body>
</html>

</x-layout>
