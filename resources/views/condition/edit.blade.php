<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div>
            <h1 class="text-base font-semibold text-gray-900">Bedingung bearbeiten</h1>
            <p class="mt-2 text-sm text-gray-700">Bearbeite die Bedingung nach Bedarf.</p>
        </div>



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

        <form action="{{ route('condition.update', $condition->id) }}" method="POST" class="sm:col-span-1 md:col-span-5">
            @csrf
            @method('PUT')

            <div class="grid  grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-4 sm:grid-cols-1 md:col-span-1">
                <div>
                    <x-input name="conditionname" type="text" placeholder="Name" label="Name" value="{{ old('conditionname', $condition->conditionname) }}" required />
                </div>

                <div class="mb-4 mt-4 sm:col-span-1">
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" id="client_id" class="w-full appearance-none rounded-md bg-white py-2 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
                        <option value="">-- Wähle einen Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ (old('client_id', $condition->client_id) == $client->id) ? 'selected' : '' }}>
                                {{ $client->clientname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">Aktualisieren</button>
                <a href="{{ route('condition.index') }}" class="ml-4 rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Zurück</a>
            </div>
        </form>
    </div>


</x-layout>
