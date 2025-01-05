<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Neue Bedingung erstellen</h1>
            <p class="mt-2 text-sm text-gray-700">Füge eine neue Bedingung hinzu, um deine Prozesse zu verwalten.</p>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg bg-white p-6">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Fehler!</strong> Bitte behebe die folgenden Probleme:<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('condition.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="conditionname" class="block text-sm font-medium text-gray-700">Name der Bedingung</label>
                            <input type="text" name="conditionname" id="conditionname" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Bedingungsname eingeben" value="{{ old('conditionname') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                            <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Wähle einen Client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->clientname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 flex items-center">
                            <input type="checkbox" name="archived" id="archived" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('archived') ? 'checked' : '' }}>
                            <label for="archived" class="ml-2 block text-sm text-gray-900">Archiviert</label>
                        </div>

                        <div class="flex items-center">
                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">Bedingung erstellen</button>
                            <a href="{{ route('condition.index') }}" class="ml-4 rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Zurück</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
