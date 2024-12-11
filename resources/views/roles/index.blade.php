<x-layout>

    <div class="sm:flex sm:items-center sm:max-w-7xl">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Rollenverwaltung</h1>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none">
            <a href="{{ route('roles.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neue Rolle hinzufügen</a>
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
                                        <a href="{{ route('roles.edit', $role->id) }}" class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Bearbeiten</a>
                                        <a href="{{ route('rolepermissions.edit', $role->id) }}" class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Rechte bearbeiten</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</x-layout>
