<x-layout>

    <div class="sm:flex sm:items-center sm:max-w-7xl">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Rollenverwaltung</h1>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none">
            <a href="{{ route('roles.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Neue Rolle hinzufügen
            </a>
        </div>
    </div>

    <div class="mt-8 flow-root sm:items-center sm:max-w-7xl">
        <div class="-mx-4 -my-2 overflow-x-auto  sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Beschreibung</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($roles as $role)
                                <tr data-id="{{ $role->id }}" class="hover:bg-indigo-100 cursor-pointer">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ $role->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $role->description }}</td>
                                    <td class="text-right whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openCopyModal({{ $role->id }}, '{{ addslashes($role->name) }}', '{{ addslashes($role->description ?? '') }}')" class="inline-flex items-center rounded-md bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                Kopieren
                                            </button>
                                            <a href="{{ route('roles.edit', $role->id) }}" class="inline-flex items-center rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Bearbeiten
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kopieren-Modal -->
    <div id="copyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Rolle kopieren</h3>
                    <button onclick="closeCopyModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form id="copyRoleForm" onsubmit="copyRole(event)">
                    <input type="hidden" id="copyRoleId" name="role_id">
                    
                    <div class="mb-4">
                        <label for="copyRoleName" class="block text-sm font-medium text-gray-700 mb-2">Neuer Rollenname:</label>
                        <input type="text" id="copyRoleName" name="name" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="z.B. Rolle - Kopie">
                        <p class="mt-1 text-xs text-gray-500">Der Name muss eindeutig sein.</p>
                    </div>

                    <div class="mb-4">
                        <label for="copyRoleDescription" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung (optional):</label>
                        <textarea id="copyRoleDescription" name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Beschreibung der neuen Rolle..."></textarea>
                    </div>

                    <div id="copyError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
                    <div id="copyLoading" class="hidden mb-4 text-center">
                        <div class="inline-flex items-center text-indigo-600">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Rolle wird kopiert...
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            Kopieren
                        </button>
                        <button type="button" onclick="closeCopyModal()" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            Abbrechen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('message'))
        <div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('message') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 3000);
        </script>
    @endif

    <script>
        let currentRoleId = null;
        let currentRoleName = '';
        let currentRoleDescription = '';

        function openCopyModal(roleId, roleName, roleDescription) {
            currentRoleId = roleId;
            currentRoleName = roleName;
            currentRoleDescription = roleDescription || '';
            
            const modal = document.getElementById('copyModal');
            const nameInput = document.getElementById('copyRoleName');
            const descriptionInput = document.getElementById('copyRoleDescription');
            const roleIdInput = document.getElementById('copyRoleId');
            
            // Vorschlag für neuen Namen (Originalname + " - Kopie")
            nameInput.value = roleName + ' - Kopie';
            descriptionInput.value = roleDescription;
            roleIdInput.value = roleId;
            
            // Fehler und Loading zurücksetzen
            document.getElementById('copyError').classList.add('hidden');
            document.getElementById('copyLoading').classList.add('hidden');
            
            modal.classList.remove('hidden');
            nameInput.focus();
            nameInput.select();
        }

        function closeCopyModal() {
            const modal = document.getElementById('copyModal');
            modal.classList.add('hidden');
            currentRoleId = null;
            document.getElementById('copyRoleForm').reset();
        }

        async function copyRole(event) {
            event.preventDefault();
            
            const nameInput = document.getElementById('copyRoleName');
            const descriptionInput = document.getElementById('copyRoleDescription');
            const errorDiv = document.getElementById('copyError');
            const loadingDiv = document.getElementById('copyLoading');
            
            const name = nameInput.value.trim();
            const description = descriptionInput.value.trim();
            
            if (!name) {
                errorDiv.textContent = 'Bitte geben Sie einen Rollennamen ein.';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            // Loading anzeigen
            errorDiv.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            
            try {
                const response = await fetch(`{{ url('/roles') }}/${currentRoleId}/copy`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description
                    })
                });
                
                const data = await response.json();
                loadingDiv.classList.add('hidden');
                
                if (response.ok && data.success) {
                    // Erfolgreich kopiert - Seite neu laden
                    window.location.href = '{{ route("roles.index") }}?message=' + encodeURIComponent(data.message);
                } else {
                    // Fehler anzeigen
                    errorDiv.textContent = data.message || 'Fehler beim Kopieren der Rolle.';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                loadingDiv.classList.add('hidden');
                errorDiv.textContent = 'Fehler bei der Verbindung zum Server: ' + error.message;
                errorDiv.classList.remove('hidden');
            }
        }

        // Modal mit ESC schließen
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCopyModal();
            }
        });

        // Modal schließen bei Klick außerhalb
        document.getElementById('copyModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeCopyModal();
            }
        });
    </script>

</x-layout>
