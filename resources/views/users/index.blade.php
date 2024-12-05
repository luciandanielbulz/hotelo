<x-layout>
    <div class="px-4 sm:px-6 lg:px-8">
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
                                                <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit" class="rounded-md bg-red-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-red-200">Passwort zurücksetzen</button>
                                                </form>
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
    </div>
</x-layout>
