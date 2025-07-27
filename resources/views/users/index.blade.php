<x-layout>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Benutzerverwaltung</h1>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none">
            <a href="{{ route('users.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neuer Benutzer hinzufügen</a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-m font-semibold text-gray-900 sm:pl-6">E-Mail/Login</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-m font-semibold text-gray-900">Vorname</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-m font-semibold text-gray-900">Nachname</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-m font-semibold text-gray-900">Role</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-m font-semibold text-gray-900">Client</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-m font-semibold text-gray-900">Aktiv</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-m font-semibold text-gray-900"></th>
                                <th scope="col" class="px-3 py-3.5 text-right text-m font-semibold text-gray-900"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($users as $user)
                                <tr data-id="{{ $user->user_id }}" class="hover:bg-indigo-100 cursor-pointer">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-m text-gray-900 sm:pl-6">{{ $user->user_email }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-m text-gray-500">{{ $user->user_name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-m text-gray-500">{{ $user->lastname }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-m text-gray-500">{{ $user->role_name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-m text-gray-500">{{ $user->clientname }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-m text-gray-500">{{ $user->isactive ? 'Ja' : 'Nein' }}</td>
                                    <td class="text-right whitespace-nowrap px-2 py-2 text-m">
                                        <a href="{{ route('users.edit', $user->user_id) }}" class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Bearbeiten</a>
                                    </td>
                                    <td class="text-right whitespace-nowrap px-2 py-2 text-m">
                                        @if (Auth::user()->hasPermission('reset_user_password'))
                                            <div class="flex space-x-2">
                                                <a href="{{ route('users.show-reset-password', $user->user_id) }}" class="rounded-md bg-red-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-red-200">Passwort zurücksetzen</a>
                                                <button onclick="diagnoseUser({{ $user->user_id }})" class="rounded-md bg-blue-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-blue-200">Diagnose</button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Diagnose Modal -->
    <div id="diagnoseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Login-Diagnose</h3>
                    <div id="diagnoseContent">
                        <div class="text-center">
                            <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full" role="status">
                                <span class="sr-only">Laden...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function diagnoseUser(userId) {
            document.getElementById('diagnoseModal').classList.remove('hidden');
            document.getElementById('diagnoseContent').innerHTML = '<div class="text-center"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full" role="status"><span class="sr-only">Laden...</span></div></div>';
            
            fetch(`/users/${userId}/diagnose-login`)
                .then(response => response.json())
                .then(data => {
                    let html = `
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold">Benutzer: ${data.user.name}</h4>
                                <p class="text-sm text-gray-600">E-Mail: ${data.user.email}</p>
                                <p class="text-sm text-gray-600">Rolle: ${data.user.role}</p>
                                <p class="text-sm text-gray-600">Client: ${data.user.client}</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold">Status:</h4>
                                <ul class="text-sm space-y-1">
                                    <li class="${data.diagnosis.is_active ? 'text-green-600' : 'text-red-600'}">
                                        ✓ Aktiv: ${data.diagnosis.is_active ? 'Ja' : 'Nein'}
                                    </li>
                                    <li class="${data.diagnosis.has_password ? 'text-green-600' : 'text-red-600'}">
                                        ✓ Passwort gesetzt: ${data.diagnosis.has_password ? 'Ja' : 'Nein'}
                                    </li>
                                    <li class="${data.diagnosis.role_exists ? 'text-green-600' : 'text-red-600'}">
                                        ✓ Rolle zugewiesen: ${data.diagnosis.role_exists ? 'Ja' : 'Nein'}
                                    </li>
                                    <li class="${data.diagnosis.client_exists ? 'text-green-600' : 'text-red-600'}">
                                        ✓ Client zugewiesen: ${data.diagnosis.client_exists ? 'Ja' : 'Nein'}
                                    </li>
                                    <li class="${data.diagnosis.client_active ? 'text-green-600' : 'text-red-600'}">
                                        ✓ Client aktiv: ${data.diagnosis.client_active ? 'Ja' : 'Nein'}
                                    </li>
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold">Empfehlungen:</h4>
                                <ul class="text-sm space-y-1">
                                    ${data.recommendations.map(rec => `<li class="text-gray-700">• ${rec}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    `;
                    document.getElementById('diagnoseContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('diagnoseContent').innerHTML = '<div class="text-red-600">Fehler beim Laden der Diagnose-Daten.</div>';
                });
        }

        function closeModal() {
            document.getElementById('diagnoseModal').classList.add('hidden');
        }

        // Modal schließen wenn außerhalb geklickt wird
        document.getElementById('diagnoseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

</x-layout>
