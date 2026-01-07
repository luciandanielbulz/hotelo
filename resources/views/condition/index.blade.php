<x-layout>
    <div class="sm:flex sm:items-center sm:max-w-7xl">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Bedingungen</h1>
            <p class="mt-2 text-sm text-gray-700">Verwalte deine Bedingungen effizient und schnell.</p>
        </div>
        <div class="mt-4 sm:ml-auto sm:mt-0 sm:flex-none space-x-2">
            <a href="{{ route('condition.trashed') }}" class="inline-block rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">📁 Papierkorb</a>
            <a href="{{ route('condition.create') }}" class="inline-block rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neue Bedingung</a>
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
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Erstellt am</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($conditions as $condition)
                                    <tr class="hover:bg-indigo-100">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ $condition->id }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $condition->conditionname }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ optional($condition->created_at)->format('d.m.Y') ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-sm">
                                            <a href="{{ route('condition.show', $condition->id) }}" class="rounded-md bg-blue-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 mr-2">Anzeigen</a>
                                            <a href="{{ route('condition.edit', $condition->id) }}" class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200 mr-2">Bearbeiten</a>
                                            <form action="{{ route('condition.destroy', $condition->id) }}" method="POST" style="display:inline-block;" onsubmit="console.log('Form submitted for condition ID:', {{ $condition->id }}); return confirm('Bist du sicher, dass du diese Bedingung löschen möchtest?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-900" onclick="console.log('Delete button clicked for condition:', {{ $condition->id }});">Löschen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @else
            <div class="mt-4 text-center text-gray-500">Keine Bedingungen gefunden.</div>
        @endif
    </div>
</x-layout>
