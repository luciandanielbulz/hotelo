<x-layout>
    <div class="sm:flex sm:items-center sm:max-w-7xl">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Gelöschte Bedingungen</h1>
            <p class="mt-2 text-sm text-gray-700">Übersicht aller gelöschten Bedingungen. Diese können wiederhergestellt oder permanent gelöscht werden.</p>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none">
            <a href="{{ route('condition.index') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">← Zurück zur Übersicht</a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($conditions->count())
        <div class="mt-8 flow-root sm:items-center sm:max-w-7xl">
            <div class="-mx-4 -my-2 overflow-x-auto  sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Gelöscht am</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($conditions as $condition)
                                    <tr class="hover:bg-red-50">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ $condition->id }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $condition->conditionname }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ optional($condition->deleted_at)->format('d.m.Y H:i') ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm">
                                            <form action="{{ route('condition.restore', $condition->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 mr-2">Wiederherstellen</button>
                                            </form>
                                            <form action="{{ route('condition.force-delete', $condition->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bist du sicher, dass du diese Bedingung PERMANENT löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-900">Permanent löschen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="mt-4 text-center text-gray-500">
                <div class="text-lg">🗑️</div>
                <div class="mt-2">Keine gelöschten Bedingungen gefunden.</div>
                <div class="text-sm text-gray-400 mt-1">Alle deine Bedingungen sind aktiv.</div>
            </div>
        @endif
    </div>
</x-layout> 